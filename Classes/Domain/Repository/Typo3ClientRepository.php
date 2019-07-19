<?php declare(strict_types=1);


namespace DFAU\ToujouOauth2Server\Domain\Repository;

use DFAU\ToujouOauth2Server\Domain\Entity\Typo3Client;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Typo3ClientRepository implements ClientRepositoryInterface
{

    const TABLE_NAME = 'tx_toujou_oauth2_server_client';

    /**
     * @var PasswordHashFactory
     */
    protected $hashFactory;

    /**
     * @var \TYPO3\CMS\Core\Database\Query\QueryBuilder
     */
    protected $queryBuilder;

    public function __construct()
    {
        $this->hashFactory = GeneralUtility::makeInstance(PasswordHashFactory::class);
        $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(static::TABLE_NAME);
    }

    public function getClientEntity($clientIdentifier)
    {
        $clientData = $this->findRawByIdentifier($clientIdentifier, ['name', 'redirect_uris', 'user_uid', 'user_table']);

        if ($clientData) {
            $userIdentifier = !empty($clientData['user_table']) && !empty($clientData['user_uid']) ? $clientData['user_table'] . '_' . $clientData['user_uid'] : null;
            return new Typo3Client(
                $clientIdentifier,
                $clientData['name'],
                GeneralUtility::trimExplode("\n", $clientData['redirect_uri']),
                $userIdentifier
            );
        }

        return null;
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        if ($client = $this->findRawByIdentifier($clientIdentifier, ['identifier', 'secret'])) {
            $hash = $this->hashFactory->get($client['secret'], 'BE');
            return $hash->checkPassword($clientSecret, $client['secret']);
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
                $this->queryBuilder->quote($clientIdentifier)
            ))
            ->setMaxResults(1)
            ->execute()
            ->fetch();
    }
}
