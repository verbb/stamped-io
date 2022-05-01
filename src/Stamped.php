<?php
namespace verbb\stamped;

use verbb\stamped\base\PluginTrait;
use verbb\stamped\models\Settings;

use Craft;
use craft\base\Model;
use craft\base\Plugin;

use craft\commerce\elements\Order;

use yii\base\Event;

class Stamped extends Plugin
{
    // Properties
    // =========================================================================

    public bool $hasCpSettings = true;
    public string $schemaVersion = '1.0.0';


    // Traits
    // =========================================================================

    use PluginTrait;


    // Public Methods
    // =========================================================================

    public function init(): void
    {
        parent::init();

        self::$plugin = $this;

        $this->_registerComponents();
        $this->_registerLogTarget();
        $this->_registerEventHandlers();
    }


    // Protected Methods
    // =========================================================================

    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('stamped-io/settings', [
            'settings' => $this->getSettings(),
        ]);
    }


    // Private Methods
    // =========================================================================

    private function _registerEventHandlers(): void
    {
        Event::on(Order::class, Order::EVENT_AFTER_COMPLETE_ORDER, [$this->getService(), 'handleCompletedOrder']);
    }

}