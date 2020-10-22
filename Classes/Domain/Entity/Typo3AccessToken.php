<?php

declare(strict_types=1);

namespace DFAU\ToujouOauth2Server\Domain\Entity;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

class Typo3AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait;
    use EntityTrait;
    use TokenEntityTrait;

    public function __construct(ClientEntityInterface $clientEntity, array $scopes = [], $userIdentifier = null)
    {
        $this->setClient($clientEntity);
        $scopes && array_map([$this, 'addScope'], $scopes);
        $userIdentifier && $this->setUserIdentifier($userIdentifier);
    }
}
