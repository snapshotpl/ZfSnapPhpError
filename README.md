ZfSnapPhpError
==============

PHP Error module for Zend Framework 2

Version 0.9.0 Created by Witold Wasiczko

![Better Error Message](http://i.imgur.com/1G77I.png)

Usage
-----
Just install module via composer and use all features of [`PHP-Error`](http://phperror.net/) project!

ZfSnapPhpError uses [`forked version of PHP-Error`](https://github.com/snapshotpl/PHP-Error) with new features and bug-fixes.

How to install?
---------------
Via [`composer`](https://getcomposer.org/)
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/snapshotpl/PHP-Error"
        }
    ],
    "require": {
        "snapshotpl/zf-snap-php-error": "dev-master"
    }
}
```

run composer update and add module ZfSnapPhpError to ZF2 application configuration.

Features
--------
* All [`PHP-Error`](http://phperror.net/) features
* Ready to use - just install via composer.json!
* easy configurable via module config,
* additional info about ZF2 application on error page:
  * service_manager
  * modules
  * current route

How to config?
--------------
Overwrite module config:
```php
<?php

return array(
    'php-error' => array(
        'enabled' => true,
        'options' => array(

        ),
    ),
);
```
* set enabled to false to disabled PHP-Error
* pass options of PHP-Error ([`more info here`](https://github.com/JosephLenton/PHP-Error/wiki/Options#all-options))
