<?php
namespace verbb\stamped\base;

use verbb\stamped\Stamped;
use verbb\stamped\services\Service;
use verbb\base\BaseHelper;

use Craft;

use yii\log\Logger;

trait PluginTrait
{
    // Properties
    // =========================================================================

    public static Stamped $plugin;


    // Static Methods
    // =========================================================================

    public static function log(string $message, array $params = []): void
    {
        $message = Craft::t('stamped-io', $message, $params);

        Craft::getLogger()->log($message, Logger::LEVEL_INFO, 'stamped-io');
    }

    public static function error(string $message, array $params = []): void
    {
        $message = Craft::t('stamped-io', $message, $params);

        Craft::getLogger()->log($message, Logger::LEVEL_ERROR, 'stamped-io');
    }


    // Public Methods
    // =========================================================================

    public function getService(): Service
    {
        return $this->get('service');
    }


    // Private Methods
    // =========================================================================

    private function _registerComponents(): void
    {
        $this->setComponents([
            'service' => Service::class,
        ]);

        BaseHelper::registerModule();
    }

    private function _registerLogTarget(): void
    {
        BaseHelper::setFileLogging('stamped-io');
    }

}