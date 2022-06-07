<?php

declare(strict_types=1);

namespace DFAU\ToujouOauth2Server\Domain\Entity;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class Typo3Client implements ClientEntityInterface, UserRelatedClientEntityInterface
{
    use EntityTrait;
    use ClientTrait;

    /** @var array */
    protected $userIdentifier;

    public function __construct($identifier, string $name, array $redirectUri, string $userIdentifier = null, bool $isConfidential = true)
    {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->redirectUri = $redirectUri;
        $this->userIdentifier = $userIdentifier;
        $this->isConfidential = $isConfidential;
    }

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }
}
