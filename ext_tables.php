<?php

defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\DFAU\ToujouOauth2Server\Form\UuidClientIdentifierGenerator::class] = [
    'depends' => [
        \TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowDefaultValues::class
    ]
];

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon('tcarecords-tx_toujou_oauth2_server_client-default', \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class, ['name' => 'id-badge']);
