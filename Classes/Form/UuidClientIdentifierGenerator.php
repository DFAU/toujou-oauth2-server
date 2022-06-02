<?php

declare(strict_types=1);

namespace DFAU\ToujouOauth2Server\Form;

use Ramsey\Uuid\Uuid;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

class UuidClientIdentifierGenerator implements FormDataProviderInterface
{
    /**
     * Add form data to result array
     *
     * @param array $result Initialized result array
     *
     * @return array Result filled with more data
     */
    public function addData(array $result): array
    {
        if ('new' !== $result['command']) {
            return $result;
        }
        if (!\is_array($result['databaseRow'])) {
            throw new \UnexpectedValueException(
                'databaseRow of table ' . $result['tableName'] . ' is not an array',
                1563458054
            );
        }

        if ('tx_toujou_oauth2_server_client' === $result['tableName']) {
            $result['databaseRow']['identifier'] = $result['databaseRow']['identifier'] ?: Uuid::uuid4();
        }

        return $result;
    }
}
