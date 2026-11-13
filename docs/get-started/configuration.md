# Configuration

You can customise Stamped.io’s settings using a PHP configuration file. This is optional: each setting has a default, so you only need to include the values you want to change.

To override a setting, create `stamped-io.php` in your Craft project’s `/config` directory and return an array of setting names and values. For example, the following will set your store hash, with your own value in place of the placeholder:

```php
<?php

return [
    'storeHash' => 'YOUR_STORE_HASH',
];
```

All other settings keep their defaults. Add any further settings you want to change to the same array. The options below explain the available settings and their defaults. You can also manage these settings in the control panel under **Settings → Stamped**.

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
