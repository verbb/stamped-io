<?php
namespace verbb\stamped\assetbundles;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

use verbb\base\assetbundles\CpAsset as VerbbCpAsset;

class StampedAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    public function init()
    {
        $this->depends = [
            VerbbCpAsset::class,
            CpAsset::class,
        ];

        parent::init();
    }
}
