# Stamped.io Plugin for Craft CMS

This plugin provides integration with [Stamped.io](https://stamped.io/) by pushing order information to Stamped.io, to be added to a queue to be sent to your customers.

## Installation
You can install Stamped.io via the plugin store, or through Composer.

### Craft Plugin Store
To install **Stamped.io**, navigate to the _Plugin Store_ section of your Craft control panel, search for `Stamped.io`, and click the _Try_ button.

### Composer
You can also add the package to your project using Composer and the command line.

1. Open your terminal and go to your Craft project:
```shell
cd /path/to/project
```

2. Then tell Composer to require the plugin, and Craft to install it:
```shell
composer require verbb/stamped-io && php craft plugin/install stamped-io
```

### Usage
In the Control Panel, go to Settings → Stamped.io, and enter a your API details from your [Stamped.io](https://stamped.io/) account.

When an order is complete, a Queue job will be created to send a payload of order data to [Stamped.io](https://stamped.io/). Order data will appear in the review queue in [Stamped.io](https://stamped.io/), where you can manage from there.

## Configuration

Create a `stamped-io.php` file under your `/config` directory with the following options available to you. You can also use multi-environment options to change these per environment.

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

### Configuration options

- `keyPublic` -Enter the API Key Public from your Stamped.io account.
- `keyPrivate` - Enter the API Key Private from your Stamped.io account.
- `storeHash` - Enter the Store Hash from your Stamped.io account.

## Show your Support

Stamped.io is licensed under the MIT license, meaning it will always be free and open source – we love free stuff! If you'd like to show your support to the plugin regardless, [Sponsor](https://github.com/sponsors/verbb) development.

<h2></h2>

<a href="https://verbb.io" target="_blank">
  <img width="100" src="https://verbb.io/assets/img/verbb-pill.svg">
</a>
