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
        if (!getenv('TYPO3_OAUTH2_PRIVATE_KEY')) {
            throw new \InvalidArgumentException('The environment variable "TYPO3_OAUTH2_PRIVATE_KEY" is empty.', 1565882899);
        }
        if (!getenv('TYPO3_OAUTH2_ENCRYPTION_KEY')) {
            throw new \InvalidArgumentException('The environment variable "TYPO3_OAUTH2_ENCRYPTION_KEY" is empty.', 1565883415);
        }
        /** @var AuthorizationServer $server */
        $server = GeneralUtility::makeInstance(
            AuthorizationServer::class,
            GeneralUtility::makeInstance(Typo3ClientRepository::class),
            GeneralUtility::makeInstance(Typo3AccessTokenRepository::class),
            GeneralUtility::makeInstance(Typo3ScopeRepository::class),
            new CryptKey(getenv('TYPO3_OAUTH2_PRIVATE_KEY')),
            Key::loadFromAsciiSafeString(getenv('TYPO3_OAUTH2_ENCRYPTION_KEY'))
        );

        $server->enableGrantType(
            new \League\OAuth2\Server\Grant\ClientCredentialsGrant(),
            new \DateInterval('P1D') // access tokens will expire after 1 hour
        );

        return $server;
    }
}
