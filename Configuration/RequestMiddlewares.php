<?php

use DFAU\ToujouApi\Middleware\JsonApiPayload;
use DFAU\ToujouApi\Middleware\ParsedBodyReset;
use DFAU\ToujouApi\Middleware\Router;

return [
    'frontend' => [
        'middlewares/payload/json-payload' => [
            'target' => \Middlewares\JsonPayload::class
        ],
        'dfau/toujou-oauth2-server/authorization-server' => [
            'target' => \DFAU\ToujouOauth2Server\Middleware\AuthorizationServerMiddleware::class,
            'after' => ['typo3/cms-frontend/site', 'middlewares/payload/json-payload'],
            'before' => ['typo3/cms-frontend/base-redirect-resolver']
        ],
//        'dfau/toujou-oauth2-server/resource-server' => [
//            'target' => \DFAU\ToujouOauth2Server\Middleware\ResourceServerMiddleware::class,
//        ],
    ]
];
