<?php
namespace verbb\stamped\controllers;

use verbb\stamped\Stamped;
use verbb\stamped\models\Settings;

use craft\web\Controller;

use yii\web\Response;

class DefaultController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionSettings(): Response
    {
        /* @var Settings $settings */
        $settings = Stamped::$plugin->getSettings();

        return $this->renderTemplate('stamped-io/settings', [
            'settings' => $settings,
        ]);
    }
}
