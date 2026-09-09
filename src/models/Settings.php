<?php
namespace verbb\stamped\models;

use craft\base\Model;

class Settings extends Model
{
    // Properties
    // =========================================================================

    public string $storeHash = '';
    public string $keyPublic = '';
    public string $keyPrivate = '';
    public string $productImageField = '';
    public string $productImageFieldTransform = '';


    // Protected Methods
    // =========================================================================

    protected function defineRules(): array
    {
        $rules = parent::defineRules();

        $rules[] = [['keyPublic', 'keyPrivate', 'storeHash'], 'trim'];
        $rules[] = [['keyPublic', 'keyPrivate', 'storeHash'], 'required'];

        return $rules;
    }

}
