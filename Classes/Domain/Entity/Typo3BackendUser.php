<?php declare(strict_types=1);


namespace DFAU\ToujouOauth2Server\Domain\Entity;


use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;

class Typo3BackendUser implements UserEntityInterface
{
    use EntityTrait;

    /**
     * @var array
     */
    protected $userData;

    public function __construct(string $identifier, array $userData)
    {
        $this->identifier = $identifier;
        $this->userData = $userData;
    }

    /**
     * @return array
     */
    public function getUserData(): array
    {
        return $this->userData;
    }
}
