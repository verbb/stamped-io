# Configuration

You can customise Stamped.io’s settings using a PHP configuration file. The file is optional; you can enter the required account credentials in the control panel instead.

To override a setting, create `stamped-io.php` in your Craft project's `/config` directory. For example, this supplies the store identifier:

```php
<?php

return [
    'storeHash' => 'YOUR_STORE_HASH',
];
```

Replace the example value with your actual store hash. The public and private API keys must also be supplied through the settings. See [Usage](docs:feature-tour/usage) for connecting your store and checking a completed order.

## Configuration Options
- `keyPublic` -Enter the API Key Public from your Stamped.io account.
::: reference
### `keyPrivate`

**Type:** `string` · **Default:** `''`

Enter the API Key Private from your Stamped.io account.
:::

::: reference
### `storeHash`

**Type:** `string` · **Default:** `''`

Enter the Store Hash from your Stamped.io account.
:::


## Control Panel
You can also manage configuration settings through the Control Panel by visiting Settings → Stamped.
