<?php
namespace verbb\stamped;

use verbb\stamped\base\PluginTrait;

use Craft;
use craft\base\Plugin;
use craft\web\View;

use craft\commerce\elements\Order;

use yii\base\Event;

class Stamped extends Plugin
{
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
        $this->_registerEventHandlers();
    }

    // Private Methods
    // =========================================================================
    
    private function _registerEventHandlers()
    {
        Event::on(Order::class, Order::EVENT_AFTER_COMPLETE_ORDER, [$this->getService(), 'handleCompletedOrder']);
    }

}