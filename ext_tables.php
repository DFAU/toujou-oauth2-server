<?php

use DFAU\ToujouOauth2Server\Form\UuidClientIdentifierGenerator;
use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowDefaultValues;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider;
defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][UuidClientIdentifierGenerator::class] = [
    'depends' => [
        DatabaseRowDefaultValues::class
    ]
];
