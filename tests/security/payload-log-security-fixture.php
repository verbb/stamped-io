<?php
namespace verbb\giftvoucher\elements {
    class Voucher
    {
    }
}

namespace verbb\stamped {
    class Stamped
    {
        public static object $plugin;
        public static array $logs = [];

        public static function info(string $message, array $params = []): void
        {
            self::$logs[] = ['level' => 'info', 'message' => $message, 'params' => $params];
        }

        public static function error(string $message, array $params = []): void
        {
            self::$logs[] = ['level' => 'error', 'message' => $message, 'params' => $params];
        }
    }
}

namespace {
    use GuzzleHttp\Handler\MockHandler;
    use GuzzleHttp\HandlerStack;
    use GuzzleHttp\Psr7\Response;
    use verbb\stamped\services\Service;

    $vendorPath = getenv('VERBB_STAMPED_TEST_VENDOR');

    if (!$vendorPath || !is_file($vendorPath . '/autoload.php')) {
        fwrite(STDERR, "Set VERBB_STAMPED_TEST_VENDOR to a Craft 5 and Commerce 5 vendor directory.\n");
        exit(2);
    }

    require $vendorPath . '/autoload.php';
    require $vendorPath . '/yiisoft/yii2/Yii.php';
    require $vendorPath . '/craftcms/cms/src/Craft.php';
    require dirname(__DIR__, 2) . '/src/services/Service.php';

    function check(string $label, bool $condition): void
    {
        if (!$condition) {
            throw new RuntimeException('Failed: ' . $label);
        }

        echo $label . ": PASS\n";
    }

    function logsContain(array $logs, string $value): bool
    {
        return str_contains(json_encode($logs, JSON_THROW_ON_ERROR), $value);
    }

    $customerEmail = 'private-customer@example.test';
    $customerFirstName = 'PrivateFirstName';
    $customerLastName = 'PrivateLastName';
    $customerCity = 'PrivateCity';
    $customerPhone = '+61-400-PRIVATE';
    $orderTotal = 249.95;
    $productTitle = 'Private Product Title';
    $productUrl = 'https://shop.example.test/private-product';
    $orderReference = 'STAMPED-100';
    $sensitiveValues = [
        $customerEmail,
        $customerFirstName,
        $customerLastName,
        $customerCity,
        $customerPhone,
        (string)$orderTotal,
        $productTitle,
        $productUrl,
    ];

    $mockHandler = new MockHandler([
        new Response(200, [], '{"ok":true}'),
        new Response(422, [], json_encode([
            'error' => 'Rejected payload for ' . $customerEmail,
            'phone' => $customerPhone,
            'product' => $productTitle,
        ], JSON_THROW_ON_ERROR)),
    ]);
    $handler = HandlerStack::create($mockHandler);

    Craft::$app = new class($handler) {
        public function __construct(private HandlerStack $handler)
        {
        }

        public function getVersion(): string
        {
            return '5.11.3';
        }

        public function getConfig(): object
        {
            return new class($this->handler) {
                public function __construct(private HandlerStack $handler)
                {
                }

                public function getConfigFromFile(string $filename): array
                {
                    return $filename === 'guzzle' ? ['handler' => $this->handler] : [];
                }

                public function getGeneral(): object
                {
                    return (object)['httpProxy' => null];
                }
            };
        }
    };

    \verbb\stamped\Stamped::$plugin = new class {
        public function getSettings(): object
        {
            return (object)[
                'keyPublic' => 'public-key',
                'keyPrivate' => 'private-key',
                'storeHash' => 'store-hash',
                'productImageField' => null,
                'productImageFieldTransform' => null,
            ];
        }
    };

    $product = new class($productTitle, $productUrl) {
        public int $id = 77;
        public object $type;

        public function __construct(public string $title, public string $url)
        {
            $this->type = (object)['name' => 'Private Product Type'];
        }
    };
    $purchasable = new class($product) {
        public function __construct(public object $product)
        {
        }
    };
    $lineItem = new class($purchasable) {
        public float $salePrice = 249.95;

        public function __construct(public object $purchasable)
        {
        }
    };
    $order = new class($lineItem, $orderReference, $customerEmail, $customerFirstName, $customerLastName, $customerCity, $customerPhone, $orderTotal) {
        public int $id = 100;
        public string $paymentCurrency = 'AUD';
        public DateTimeImmutable $dateOrdered;
        public object $billingAddress;
        public array $lineItems;

        public function __construct(
            object $lineItem,
            public string $reference,
            public string $email,
            string $firstName,
            string $lastName,
            string $city,
            string $phone,
            public float $totalPrice,
        ) {
            $this->dateOrdered = new DateTimeImmutable('2026-10-03T10:00:00+10:00');
            $this->billingAddress = (object)[
                'firstName' => $firstName,
                'lastName' => $lastName,
                'city' => $city,
                'phone' => $phone,
            ];
            $this->lineItems = [$lineItem];
        }
    };

    $service = new Service();
    \verbb\stamped\Stamped::$logs = [];
    $success = $service->sendOrderToStamped($order);
    $successRequest = $mockHandler->getLastRequest();
    $sentPayload = json_decode((string)$successRequest->getBody(), true, 512, JSON_THROW_ON_ERROR);

    check('A successful order is still sent to Stamped', $success === true);
    check('The outbound request retains customer and product data', $sentPayload[0]['email'] === $customerEmail
        && $sentPayload[0]['phoneNumber'] === $customerPhone
        && $sentPayload[0]['itemsList'][0]['productTitle'] === $productTitle);
    check('Preparation logging remains available', logsContain(\verbb\stamped\Stamped::$logs, 'Preparing order #' . $orderReference));
    check('Success logging remains available', logsContain(\verbb\stamped\Stamped::$logs, 'sent to Stamped successfully'));

    foreach ($sensitiveValues as $sensitiveValue) {
        check('Success logs exclude sensitive value ' . $sensitiveValue, !logsContain(\verbb\stamped\Stamped::$logs, $sensitiveValue));
    }

    \verbb\stamped\Stamped::$logs = [];
    $failure = $service->sendOrderToStamped($order);
    $errorLogs = array_values(array_filter(
        \verbb\stamped\Stamped::$logs,
        static fn(array $log): bool => $log['level'] === 'error',
    ));

    check('An HTTP error still reports failure', $failure === false);
    check('Failure logging retains the order reference', ($errorLogs[0]['params']['order'] ?? null) === $orderReference);
    check('Failure logging retains the exception class', ($errorLogs[0]['params']['exception'] ?? null) === GuzzleHttp\Exception\ClientException::class);

    foreach ($sensitiveValues as $sensitiveValue) {
        check('Failure logs exclude sensitive value ' . $sensitiveValue, !logsContain(\verbb\stamped\Stamped::$logs, $sensitiveValue));
    }
}
