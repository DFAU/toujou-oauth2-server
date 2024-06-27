<?php

use DFAU\ToujouOauth2Server\Form\UuidClientIdentifierGenerator;
use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowDefaultValues;

defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][UuidClientIdentifierGenerator::class] = [
    'depends' => [
        DatabaseRowDefaultValues::class,
    ],
];
