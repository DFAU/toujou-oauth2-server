<?php declare(strict_types=1);


namespace DFAU\ToujouOauth2Server\Middleware;


use Defuse\Crypto\Key;
use DFAU\ToujouOauth2Server\Domain\Repository\Typo3AccessTokenRepository;
use DFAU\ToujouOauth2Server\Domain\Repository\Typo3ClientRepository;
use DFAU\ToujouOauth2Server\Domain\Repository\Typo3ScopeRepository;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AuthorizationServerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $site = $request ? $request->getAttribute('site') : null;
        $tokenEndpoint = $site instanceof Site ? ltrim($site->getAttribute('oauth2TokenEndpoint') ?? '', '/ ') : null;

        if (!empty($tokenEndpoint) && GeneralUtility::isFirstPartOfStr($request->getUri()->getPath(), '/' . $tokenEndpoint)) {
            try {
                return $this->createServer()->respondToAccessTokenRequest($request, new Response());
            } catch (OAuthServerException $exception) {
                return $exception->generateHttpResponse(new Response());
            } catch (Exception $exception) {
                return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                    ->generateHttpResponse(new Response());
            }
        }

        return $handler->handle($request);
    }

    protected function createServer(): AuthorizationServer
    {
        /** @var AuthorizationServer $server */
        $server = GeneralUtility::makeInstance(
            AuthorizationServer::class,
            GeneralUtility::makeInstance(Typo3ClientRepository::class),
            GeneralUtility::makeInstance(Typo3AccessTokenRepository::class),
            GeneralUtility::makeInstance(Typo3ScopeRepository::class),
            new CryptKey('file:///Users/tmaroschik/Sites/toujou/private.key', 'password'),
            Key::loadFromAsciiSafeString('def000009a48120186ffeb187910ae37c21cc56ef2ef6cfd24b735d47a8cd0b115f53fa5ed47b6ffc39d0f4074ee789b5cec9999471b69b3f0aa5d1244fd688a710e3af8')
        );

        $server->enableGrantType(
            new \League\OAuth2\Server\Grant\ClientCredentialsGrant(),
            new \DateInterval('PT1H') // access tokens will expire after 1 hour
        );

        return $server;
    }
}
