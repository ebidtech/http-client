# HTTP Client #

Helper trait for creating HTTP Rest clients

[![Latest Stable Version](https://poser.pugx.org/ebidtech/http-client/v/stable.png)](https://packagist.org/packages/ebidtech/http-client)

## Requirements ##

* PHP >= 5.3

## Installation ##

The recommended way to install is through composer.

Just create a `composer.json` file for your project:

``` json
{
    "require": {
        "ebidtech/http-client": "@stable"
    }
}
```

**Tip:** browse [`ebidtech/http-client`](https://packagist.org/packages/ebidtech/http-client) page to choose a stable version to use, avoid the `@stable` meta constraint.

And run these two commands to install it:

```bash
$ curl -sS https://getcomposer.org/installer | php
$ composer install
```

Now you can add the autoloader, and you will have access to the library:

```php
<?php

require 'vendor/autoload.php';
```

## Usage ##

```php
<?php

namespace XXX;

use EBT\HttpClient\CreateTrait as HttpClientCreateTrait;
use Guzzle\Http\Client as GuzzleHttpClient;

class <HttpClient> {
    use HttpClientCreateTrait;

    /**
     * @var GuzzleHttpClient
     */
    private $client;

    ...
    $this->client = $this->create($host, $userAgent, $config);
}
```

## Contributing ##

See CONTRIBUTING file.

## Credits ##

* Ebidtech developer team, http-client
* [All contributors](https://github.com/ebidtech/http-client/contributors)

## License ##

http-client library is released under the MIT License. See the bundled LICENSE file for details.

