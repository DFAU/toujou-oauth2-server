<?php

// And add it to showitem
if (strpos($GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'], '--div--;API,') === false) {
    $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] .= ', --div--;API';
}

$GLOBALS['TCA']['be_users']['columns']['oauth2_clients'] = [
    'exclude' => 1,
    'label' => 'LLL:EXT:toujou_oauth2_server/Resources/Private/Language/locallang_be_users_tca.xlf:be_users.oauth2_clients.label',
    'config' => [
        'type' => 'inline',
        'foreign_table' => 'tx_toujou_oauth2_server_client',
        'foreign_field' => 'user_uid',
        'foreign_table_field' => 'user_table',
        'size' => 10,
        'maxitems' => 9999,
        'autoSizeMax' => 30,
        'multiple' => 0,
        'appearance' => [
            'collapseAll' => 1,
            'levelLinksPosition' => 'top',
            'showSynchronizationLink' => 1,
            'showPossibleLocalizationRecords' => 1,
            'showAllLocalizationLink' => 1,
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'be_users',
    '--div--;LLL:EXT:toujou_oauth2_server/Resources/Private/Language/locallang_be_users_tca.xlf:be_users.oauth2Tab,oauth2_clients'
);
