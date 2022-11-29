<?php

declare(strict_types=1);

use Middlewares\JsonPayload;
use DFAU\ToujouOauth2Server\Middleware\AuthorizationHeaderFixer;
use DFAU\ToujouOauth2Server\Middleware\AuthorizationServerMiddleware;

return [
    'frontend' => [
        'middlewares/payload/json-payload' => [
            'target' => JsonPayload::class,
        ],
        'dfau/toujou-oauth2-server/authorization-header-fixer' => [
            'target' => AuthorizationHeaderFixer::class,
            'before' => ['dfau/toujou-oauth2-server/authorization-server'],
        ],
        'dfau/toujou-oauth2-server/authorization-server' => [
            'target' => AuthorizationServerMiddleware::class,
            'after' => ['typo3/cms-frontend/site', 'middlewares/payload/json-payload'],
            'before' => ['typo3/cms-frontend/base-redirect-resolver'],
        ],
        /*'dfau/toujou-oauth2-server/resource-server' => [
            'target' => \DFAU\ToujouOauth2Server\Middleware\ResourceServerMiddleware::class,
        ],*/
    ],
];
