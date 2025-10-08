<?php

declare(strict_types=1);

namespace DFAU\ToujouOauth2Server\Domain\Repository;

use DFAU\ToujouOauth2Server\Domain\Entity\Typo3AccessToken;
use DFAU\ToujouOauth2Server\Domain\Entity\UserRelatedClientEntityInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Typo3AccessTokenRepository implements AccessTokenRepositoryInterface
{
    public const TABLE_NAME = 'tx_toujou_oauth2_server_access_token';

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        if (null === $userIdentifier && $clientEntity instanceof UserRelatedClientEntityInterface) {
            $userIdentifier = $clientEntity->getUserIdentifier();
        }

        return new Typo3AccessToken($clientEntity, $scopes, $userIdentifier);
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        // TODO implement UniqueTokenIdentifierConstraintViolationException
        $this->createQueryBuilder()
            ->insert(static::TABLE_NAME)->values([
                'identifier' => $accessTokenEntity->getIdentifier(),
                'client_id' => $accessTokenEntity->getClient()->getIdentifier(),
                'expiry_date' => $accessTokenEntity->getExpiryDateTime()->format('Y-m-d h:m:s'),
                'scopes' => \implode("\n", $accessTokenEntity->getScopes()),
            ])->executeStatement();
    }

    public function revokeAccessToken($tokenId): void
    {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder
            ->update(static::TABLE_NAME)
            ->set('revoked', \date('Y-m-d'))
            ->where($queryBuilder->expr()->eq('identifier', $queryBuilder->quote($tokenId)))
            ->executeStatement();
    }

    public function isAccessTokenRevoked($tokenId): bool
    {
        $queryBuilder = $this->createQueryBuilder();

        return (bool) $queryBuilder
            ->select('revoked')
            ->from(static::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->eq('identifier', $queryBuilder->quote($tokenId)),
                $queryBuilder->expr()->lt('revoked', 'NOW()')
            )->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();
    }

    protected function createQueryBuilder(): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(static::TABLE_NAME);
    }
}
