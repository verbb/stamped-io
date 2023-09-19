# Configuration
Create a `stamped-io.php` file under your `/config` directory with the following options available to you. You can also use multi-environment options to change these per environment.

The below shows the defaults already used by Stamped.io, so you don't need to add these options unless you want to modify the values.

```php
<?php

return [
    '*' => [
        'keyPublic' => '',
        'keyPrivate' => '',
        'storeHash' => '',
    ],
];
```

## Configuration options
- `keyPublic` -Enter the API Key Public from your Stamped.io account.
- `keyPrivate` - Enter the API Key Private from your Stamped.io account.
- `storeHash` - Enter the Store Hash from your Stamped.io account.

## Control Panel
You can also manage configuration settings through the Control Panel by visiting Settings → Stamped.
