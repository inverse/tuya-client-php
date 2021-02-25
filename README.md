# API Client for Tuya

![CI](https://github.com/inverse/tuya-client-php/workflows/CI/badge.svg)
[![codecov](https://codecov.io/gh/inverse/tuya-client-php/branch/master/graph/badge.svg?token=QRAPALSCXJ)](https://codecov.io/gh/inverse/tuya-client-php)
[![Author](https://img.shields.io/badge/author-@inverse-blue.svg?style=flat-square)](https://github.com/inverse)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/inverse/tuya-client.svg?style=flat-square)](https://packagist.org/packages/inverse/tuya-client)
[![Total Downloads](https://img.shields.io/packagist/dt/inverse/tuya-client.svg?style=flat-square)](https://packagist.org/packages/inverse/tuya-client)

An WIP API Client for controlling [Tuya][1] products, heavily inspired by [tuyapy][0].

## Currently Supporting

- Switch devices

## Installation

```
composer require inverse/tuya-client
```

## Example

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Inverse\TuyaClient\Session;
use Inverse\TuyaClient\ApiClient;
use Inverse\TuyaClient\Device\SwitchDevice;
use Inverse\TuyaClient\BizType;

// Setup credentials
$username = getenv('TUYA_USERNAME');
$password = getenv('TUYA_PASSWORD');
$countryCode = getenv('TUYA_COUNTRYCODE');
$bizType = new BizType(BizType::TUYA);

// Make client
$session = new Session($username, $password, $countryCode, $bizType);
$apiClient = new ApiClient($session);

// Get all devices
$devices = $apiClient->discoverDevices();

// Switch on all switches
foreach ($devices as $device) {
    if ($device instanceOf SwitchDevice) {
        if (!$device->isOn()) {
            $apiClient->sendEvent($device->getOnEvent());
        }
    }
}
```

## Testing

Copy `phpunit.xml.dist` to `phpunit.xml` and replace the server variables with your credentials.

## Licence

MIT

[0]: https://pypi.org/project/tuyapy
[1]: https://www.tuya.com/
