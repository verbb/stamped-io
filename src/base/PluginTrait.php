<?php
namespace verbb\stamped\base;

use verbb\stamped\Stamped;
use verbb\stamped\services\Service;

use Craft;

use putyourlightson\logtofile\LogToFile;

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
        LogToFile::info($message, 'stamped');
    }

    public static function error($message)
    {
        LogToFile::error($message, 'stamped');
    }


    // Private Methods
    // =========================================================================

    private function _setPluginComponents()
    {
        $this->setComponents([
            'service' => Service::class,
        ]);
    }

}