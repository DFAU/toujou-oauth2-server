<?php

declare(strict_types=1);

namespace DFAU\ToujouOauth2Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Fixes situations with rewrite rules in apache when HTTP Headers get a REDIRECT_ prefix
 */
class AuthorizationHeaderFixer implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$request->hasHeader('authorization') && !empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $request = $request->withAddedHeader('Authorization', $_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
        }

        return $handler->handle($request);
    }
}
