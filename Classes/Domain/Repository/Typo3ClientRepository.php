<?php

declare(strict_types=1);

namespace DFAU\ToujouOauth2Server\Domain\Repository;

use DFAU\ToujouOauth2Server\Domain\Entity\Typo3Client;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Typo3ClientRepository implements ClientRepositoryInterface
{
    public const TABLE_NAME = 'tx_toujou_oauth2_server_client';

    /** @var PasswordHashFactory */
    protected $hashFactory;

    /** @var QueryBuilder */
    protected $queryBuilder;

    public function __construct()
    {
        $this->hashFactory = GeneralUtility::makeInstance(PasswordHashFactory::class);
        $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(static::TABLE_NAME);
    }

    public function getClientEntity($clientIdentifier): ?Typo3Client
    {
        $clientData = $this->findRawByIdentifier($clientIdentifier, ['name', 'redirect_uris', 'user_uid', 'user_table']);

        if ($clientData) {
            $userIdentifier = !empty($clientData['user_table']) && !empty($clientData['user_uid']) ? $clientData['user_table'] . '_' . $clientData['user_uid'] : null;
            return new Typo3Client(
                $clientIdentifier,
                $clientData['name'],
                GeneralUtility::trimExplode("\n", $clientData['redirect_uris']),
                $userIdentifier
            );
        }

        return null;
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        if ($client = $this->findRawByIdentifier($clientIdentifier, ['identifier', 'secret'])) {
            return $this->hashFactory->get($client['secret'], 'BE')->checkPassword($clientSecret, $client['secret']);
        }

        return false;
    }

    protected function findRawByIdentifier(string $clientIdentifier, $selects = ['*']): ?array
    {
        return $this->queryBuilder
            ->resetQueryParts()
            ->select(...$selects)
            ->from(static::TABLE_NAME)
            ->where($this->queryBuilder->expr()->eq(
                'identifier',
                $this->queryBuilder->createNamedParameter($clientIdentifier)
            ))
            ->setMaxResults(1)
            ->execute()
            ->fetch() ?: null;
    }
}
