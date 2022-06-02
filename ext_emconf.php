<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'toujou Oauth2 Server',
    'description' => 'A TYPO3 Oauth2 Client Credentials Server, that logs in Backend Users',
    'category' => 'services',
    'version' => '0.0.1',
    'state' => 'beta',
    'clearCacheOnload' => true,
    'author' => 'Thomas Maroschik',
    'author_email' => 'tmaroschik@dfau.de',
    'author_company' => 'DFAU',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0 - 11.99.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ]
];
