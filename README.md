# API Client for Tuya

An WIP API Client build for [Tuya][1] heavily inspired by [tuyapy][0].

## Currently Supporting

- Switch devices

## Example

```php
require __DIR__ . '../vendor/autoload.php';

use Inverse\TuyaClient\Session;
use Inverse\TuyaClient\ApiClient;
use Inverse\TuyaClient\Device\SwitchDevice;

// Setup credentials
$username = getenv('TUYA_USERNAME');
$password = getenv('TUYA_PASSWORD');
$countryCode = getenv('TUYA_COUNTRYCODE');

// Make client
$session = new Session($username, $password, $countryCode);
$apiClient = new ApiClient($session);

// Get all devices
$devices = $apiClient->discoverDevices();

// Switch on all switches
foreach ($devices as $device) {
    if ($device instanceOf SwitchDevice) {
        $apiClient->sendEvent($device->getOnEvent());
    }
}
```

## Testing

Copy `phpunit.xml.dist` to `phpunit.xml` and replace the server variables with your credentials.

## Licence

MIT

[0]: https://pypi.org/project/tuyapy
[1]: https://www.tuya.com/