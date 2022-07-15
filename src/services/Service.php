<?php
namespace verbb\stamped\services;

use verbb\stamped\Stamped;
use verbb\stamped\queue\jobs\SendOrder;

use verbb\giftvoucher\elements\Voucher;

use Craft;
use craft\base\Component;
use craft\helpers\Json;

use craft\commerce\Plugin as Commerce;
use craft\commerce\elements\Order;

use yii\base\Event;

class Service extends Component
{
    // Public Methods
    // =========================================================================

    public function handleCompletedOrder(Event $event)
    {
        Stamped::log('Order #' . $event->sender->reference . ' completed.');

        // Trigger the actual logic in a queue so we're not holding up the main thread
        Craft::$app->getQueue()->priority(1)->push(new SendOrder([
            'orderReference' => $event->sender->reference,
        ]));
    }

    public function sendOrderToStamped($order)
    {
        try {
            Stamped::log('Preparing order #' . $order->reference . ' to be sent to Stamped.');

            $payload = $this->_getPayload($order);

            Stamped::log(Json::encode($payload));

            $response = $this->_request('POST', 'survey/reviews/bulk', [
                'json' => [$payload],
            ]);

            Stamped::log('Order #' . $order->reference . ' sent to Stamped successfully.');

            return true;
        } catch (\Throwable $e) {
            Stamped::error(Craft::t('app', '{e} - {f}: {l}.', [
                'e' => $e->getMessage(),
                'f' => $e->getFile(),
                'l' => $e->getLine(),
            ]));
        }

        return false;
    }


    // Private Methods
    // =========================================================================

    private function _getPayload($order)
    {
        $settings = Stamped::$plugin->getSettings();

        $payload = [
            'email' => $order->email,
            'firstName' => $order->billingAddress->firstName ?? '',
            'lastName' => $order->billingAddress->lastName ?? '',
            'location' => $order->billingAddress->city ?? '',
            'phoneNumber' => $order->billingAddress->phone ?? '',

            'orderNumber' => $order->id,
            'orderId' => $order->id,
            'orderCurrencyISO' => $order->paymentCurrency,
            'orderTotalPrice' => $order->totalPrice,
            'orderSource' => 'web',
            'source' => 'web',
            'orderDate' => $order->dateOrdered->format('c'),
        ];

        foreach ($order->lineItems as $key => $lineItem) {
            if ($lineItem->purchasable instanceof Voucher) {
                $product = $lineItem->purchasable;
            } else {
                $product = $lineItem->purchasable->product;
            }

            // Fetch the image field. Will handle if the chosen asset field is on the product or variant
            if ($productImageField = $settings->productImageField) {
                $variant = $lineItem->purchasable;

                if ($variant->$productImageField && $variant->$productImageField->count()) {
                    if ($image = $variant->$productImageField->one()) {
                        $imageUrl = $image->getUrl($settings->productImageFieldTransform, true);
                    }
                } else if ($product->$productImageField && $product->$productImageField->count()) {
                    if ($image = $product->$productImageField->one()) {
                        $imageUrl = $image->getUrl($settings->productImageFieldTransform, true);
                    }
                }
            }

            $payload['itemsList'][] = [
                'productId' => $product->id ?? '',
                'productBrand' => '',
                'productDescription' => $product->title ?? '',
                'productTitle' => $product->title ?? '',
                'productImageUrl' => $imageUrl ?? '',
                'productPrice' => $lineItem->salePrice ?? '',
                'productType' => $product->type->name ?? '',
                'productUrl' => $product->url ?? '',
            ];
        }

        return $payload;
    }

    private function _getClient()
    {
        $settings = Stamped::$plugin->getSettings();
        $keyPublic = Craft::parseEnv($settings->keyPublic);
        $keyPrivate = Craft::parseEnv($settings->keyPrivate);
        $storeHash = Craft::parseEnv($settings->storeHash);

        return Craft::createGuzzleClient([
            'base_uri' => "https://stamped.io/api/v2/{$storeHash}/",
            'auth' => [$keyPublic, $keyPrivate],
        ]);
    }

    private function _request(string $method, string $uri, array $options = [])
    {
        $response = $this->_getClient()->request($method, ltrim($uri, '/'), $options);

        return Json::decode((string)$response->getBody());
    }

}
