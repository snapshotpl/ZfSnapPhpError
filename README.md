ZfSnapPhpError
==============

PHP Error module for Zend Framework 2

Version 1.0.0 Created by Witold Wasiczko

![Better Error Message](http://www.psd2html.pl/public/ZfSnapPhpError/head.png)

Usage
-----
Just install module via composer and use all features of [PHP-Error](http://phperror.net/) project!

ZfSnapPhpError uses [forked version of PHP-Error](https://github.com/snapshotpl/PHP-Error) with new features and bug-fixes.

How to install?
---------------
By [composer.json](https://getcomposer.org/)
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/snapshotpl/PHP-Error"
        }
    ],
    "require": {
        "snapshotpl/zf-snap-php-error": "1.*"
    }
}
```

run `composer update` and add module `ZfSnapPhpError` to ZF2 `application.config.php`

Features
--------
* All [PHP-Error](http://phperror.net/) features
  * Catch php errors,
  * Catch php exceptions,
  * Works with ajax requests!
  * and [more](http://phperror.net/)...!
* Ready to use - just install via composer.json!
* easy configurable via module config,
* additional info about ZF2 application on error page:
  * service_manager,
  * modules,
  * current route,
* access to `\php_error\ErrorHandler` object by service manager (key `phperror`)

![Better Error Message](http://www.psd2html.pl/public/ZfSnapPhpError/custom.png)

How to config?
--------------
Overwrite module config:
```php
<?php

return array(
    'php-error' => array(
        'enabled' => true,
        'options' => array(),
    ),
);
```
* set `enabled` to `false` to disabled PHP-Error
* set `options` of PHP-Error ([more info here](https://github.com/JosephLenton/PHP-Error/wiki/Options#all-options))
