<?php
namespace verbb\stamped\models;

use craft\base\Model;

class Settings extends Model
{
    // Public Properties
    // =========================================================================

    public $storeHash = '';
    public $keyPublic = '';
    public $keyPrivate = '';
    public $productImageField = '';
    public $productImageFieldTransform = '';

}
