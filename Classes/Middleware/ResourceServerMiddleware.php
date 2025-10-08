<?php

declare(strict_types=1);

namespace DFAU\ToujouOauth2Server\Middleware;

use DFAU\ToujouOauth2Server\Domain\Repository\Typo3AccessTokenRepository;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Backend\FrontendBackendUserAuthentication;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ResourceServerMiddleware implements MiddlewareInterface
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
        try {
            $request = $this->createServer()->validateAuthenticatedRequest($request);
            $this->overrideBackendUser($request->getAttribute('oauth_user_id'));
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse(new Response());
        } catch (\Throwable $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse(new Response());
        }

        return $handler->handle($request);
    }

    protected function createServer(): ResourceServer
    {
        if (!\getenv('TYPO3_OAUTH2_PUBLIC_KEY')) {
            throw new \InvalidArgumentException('The environment variable "TYPO3_OAUTH2_PUBLIC_KEY" is empty.', 1565883420);
        }
        /** @var ResourceServer $server */
        $server = GeneralUtility::makeInstance(
            ResourceServer::class,
            GeneralUtility::makeInstance(Typo3AccessTokenRepository::class),
            new CryptKey(\getenv('TYPO3_OAUTH2_PUBLIC_KEY'))
        );

        return $server;
    }

    protected function overrideBackendUser($userIdentifier): void
    {
        if (null !== $userIdentifier && \str_starts_with($userIdentifier, 'be_users_')) {
            $backendUserObject = GeneralUtility::makeInstance(FrontendBackendUserAuthentication::class);
            $backendUserObject->user = $backendUserObject->getRawUserByUid(BackendUtility::splitTable_Uid($userIdentifier)[1]);
            if (!empty($backendUserObject->user['uid'])) {
                $backendUserObject->loginFailure = false;
                $backendUserObject->fetchGroupData();
                $backendUserObject->createUserSession($backendUserObject->user);

                $GLOBALS['BE_USER'] = $backendUserObject;

                Bootstrap::loadExtTables();

                // Override the backend user for this request if oauth2 authentication succeeds
                GeneralUtility::makeInstance(Context::class)->setAspect(
                    'backend.user',
                    GeneralUtility::makeInstance(UserAspect::class, $backendUserObject)
                );
            }
        }
    }
}
