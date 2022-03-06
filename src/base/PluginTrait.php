<?php
namespace verbb\stamped\base;

use verbb\stamped\Stamped;
use verbb\stamped\services\Service;

use Craft;

use yii\log\Logger;

use verbb\base\BaseHelper;

trait PluginTrait
{
    // Properties
    // =========================================================================

    public static Stamped $plugin;


    // Static Methods
    // =========================================================================

    public static function log($message): void
    {
        Craft::getLogger()->log($message, Logger::LEVEL_INFO, 'stamped-io');
    }

    public static function error($message): void
    {
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

    private function _setPluginComponents(): void
    {
        $this->setComponents([
            'service' => Service::class,
        ]);

        BaseHelper::registerModule();
    }

    private function _setLogging(): void
    {
        BaseHelper::setFileLogging('stamped-io');
    }

}