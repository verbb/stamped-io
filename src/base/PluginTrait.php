<?php
namespace verbb\stamped\base;

use verbb\stamped\Stamped;
use verbb\stamped\services\Service;

use Craft;
use craft\log\FileTarget;

use yii\log\Logger;

use verbb\base\BaseHelper;

trait PluginTrait
{
    // Static Properties
    // =========================================================================

    public static $plugin;


    // Public Methods
    // =========================================================================

    public function getService()
    {
        return $this->get('service');
    }

    public static function log($message)
    {
        Craft::getLogger()->log($message, Logger::LEVEL_INFO, 'stamped-io');
    }

    public static function error($message)
    {
        Craft::getLogger()->log($message, Logger::LEVEL_ERROR, 'stamped-io');
    }


    // Private Methods
    // =========================================================================

    private function _setPluginComponents()
    {
        $this->setComponents([
            'service' => Service::class,
        ]);

        BaseHelper::registerModule();
    }

    private function _setLogging()
    {
        BaseHelper::setFileLogging('stamped-io');
    }

}