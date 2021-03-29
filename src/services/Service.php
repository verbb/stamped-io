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
                'l' => $e->getLine()
            ]));
        }

        return false;
    }


    // Private Methods
    // =========================================================================

    private function _getPayload($order)
    {
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

            $payload['itemsList'][] = [
                'productId' => $product->id ?? '',
                'productBrand' => '',
                'productDescription' => $product->title ?? '',
                'productTitle' => $product->title ?? '',
                'productImageUrl' => '',
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

        return Craft::createGuzzleClient([
            'base_uri' => "https://stamped.io/api/v2/{$settings->storeHash}/",
            'auth' => [$settings->keyPublic, $settings->keyPrivate],
        ]);
    }

    private function _request(string $method, string $uri, array $options = [])
    {
        $response = $this->_getClient()->request($method, ltrim($uri, '/'), $options);

        return Json::decode((string)$response->getBody());
    }

}
