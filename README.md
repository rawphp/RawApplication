# RawApplication - A Simple App Class for PHP Applications

[![Build Status](https://travis-ci.org/rawphp/RawApplication.svg?branch=master)](https://travis-ci.org/rawphp/RawApplication) [![Coverage Status](https://coveralls.io/repos/rawphp/RawApplication/badge.png?branch=master)](https://coveralls.io/r/rawphp/RawApplication?branch=master)

[![Latest Stable Version](https://poser.pugx.org/rawphp/raw-application/v/stable.svg)](https://packagist.org/packages/rawphp/raw-application) [![Total Downloads](https://poser.pugx.org/rawphp/raw-application/downloads.svg)](https://packagist.org/packages/rawphp/raw-application) [![Latest Unstable Version](https://poser.pugx.org/rawphp/raw-application/v/unstable.svg)](https://packagist.org/packages/rawphp/raw-application) [![License](https://poser.pugx.org/rawphp/raw-application/license.svg)](https://packagist.org/packages/rawphp/raw-application)

## Package Features
- A central class for an application
- Provides access to the request, session, database, router, etc.

## Installation

### Composer
RawApplication is available via [Composer/Packagist](https://packagist.org/packages/rawphp/raw-application).

Add `"rawphp/raw-application": "0.*@dev"` to the require block in your composer.json and then run `composer install`.

```json
{
        "require": {
            "rawphp/raw-application": "0.*@dev"
        }
}
```

You can also simply run the following from the command line:

```sh
composer require rawphp/raw-application "0.*@dev"
```

### Tarball
Alternatively, just copy the contents of the RawApplication folder into somewhere that's in your PHP `include_path` setting. If you don't speak git or just want a tarball, click the 'zip' button at the top of the page in GitHub.

## Basic Usage

```php
<?php

use RawPHP\RawApplication\Application;

// create your application class by extending the base Application class
class CoolApp extends Application
{

}

// declare configuration for the app (see example in package)
$config = array(
    'application_name' => 'Cool App',
    ...
);

$app = new CoolApp( );

// initialise the app
$app->init( $config );

// run the app
$app->run( );
```

## License
This package is licensed under the [MIT](https://github.com/rawphp/RawApplication/blob/master/LICENSE). Read LICENSE for information on the software availability and distribution.

## Contributing

Please submit bug reports, suggestions and pull requests to the [GitHub issue tracker](https://github.com/rawphp/RawApplication/issues).

## Changelog

#### 22-09-2014
- Updated to PHP 5.3.

#### 20-09-2014
- Replaced php array configuration with yaml

#### 19-09-2014
- Updated application configuration
- Added debug flag for application components in config.

#### 18-09-2014
- Updated to work with the latest rawphp/rawbase package.

#### 17-09-2014
- Reduced component dependency to just the essentials in composer.

#### 14-09-2014
- Initial Code Commit