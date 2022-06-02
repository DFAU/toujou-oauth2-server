# Toujou OAuth 2.0 Server

@TODO add description

## Installation

Require and install the plugin

    $ composer require DFAU/toujou-oauth2-server
    $ vendor/bin/typo3cms extension:install toujou_oauth2_server

## Development

Install php dependencies using composer:

    $ composer install

#### [PHPUnit](https://phpunit.de) Unit tests

    $ etc/scripts/runTests.sh

#### [PHPUnit](https://phpunit.de) Functional tests

    $ etc/scripts/runTests.sh -s functional


#### [Easy-Coding-Standard](https://github.com/Symplify/EasyCodingStandard)

Check coding standard violations

    $ etc/scripts/checkCodingStandards.sh

Fix coding standard violations automatically

    $ etc/scripts/checkCodingStandards.sh --fix
