<?php
namespace verbb\stamped;

use verbb\stamped\base\PluginTrait;
use verbb\stamped\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\web\View;

use craft\commerce\elements\Order;

use yii\base\Event;

class Stamped extends Plugin
{
    // Public Properties
    // =========================================================================

    public $schemaVersion = '1.0.0';
    public $hasCpSettings = true;


    // Traits
    // =========================================================================

    use PluginTrait;

    
    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        self::$plugin = $this;

        $this->_setPluginComponents();
        $this->_setLogging();
        $this->_registerEventHandlers();
    }


    // Protected Methods
    // =========================================================================

    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }

    protected function settingsHtml()
    {
        return Craft::$app->view->renderTemplate('stamped-io/settings', [
            'settings' => $this->getSettings(),
        ]);
    }
    

    // Private Methods
    // =========================================================================
    
    private function _registerEventHandlers()
    {
        Event::on(Order::class, Order::EVENT_AFTER_COMPLETE_ORDER, [$this->getService(), 'handleCompletedOrder']);
    }

}