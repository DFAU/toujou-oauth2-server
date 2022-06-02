<?php

// Configure a new simple required input field to site
$GLOBALS['SiteConfiguration']['site']['columns']['oauth2TokenEndpoint'] = [
    'label' => 'LLL:EXT:toujou_oauth2_server/Resources/Private/Language/locallang_siteconfiguration_tca.xlf:site.oauth2TokenEndpoint.label',
    'description' => 'LLL:EXT:toujou_oauth2_server/Resources/Private/Language/locallang_siteconfiguration_tca.xlf:site.oauth2TokenEndpoint.description',
    'config' => [
        'type' => 'input',
        'eval' => 'trim',
        'default' => '_api/token',
        'valuePicker' => [
            'items' => [
                ['_api/token', '_api/token'],
            ],
        ],
    ],
];
// And add it to showitem
if (false === \strpos($GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'], '--div--;API,')) {
    $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] .= ', --div--;API';
}
$GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] = \str_replace(
    '--div--;API',
    '--div--;API, oauth2TokenEndpoint,',
    $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem']
);
