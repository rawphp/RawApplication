# RawApplication - A Simple App Class for PHP Applications

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

#### 14-09-2014
- Initial Code Commit
