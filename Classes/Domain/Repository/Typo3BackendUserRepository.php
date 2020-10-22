<?php

declare(strict_types=1);

namespace DFAU\ToujouOauth2Server\Domain\Repository;

use DFAU\ToujouOauth2Server\Authentication\Oauth2BackendUserAuthentication;
use DFAU\ToujouOauth2Server\Domain\Entity\Typo3BackendUser;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use TYPO3\CMS\Core\Authentication\LoginType;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Typo3BackendUserRepository implements UserRepositoryInterface
{
    /**
     * @var Oauth2BackendUserAuthentication
     */
    protected $backendUser;

    public function __construct()
    {
        $this->backendUser = GeneralUtility::makeInstance(Oauth2BackendUserAuthentication::class);
    }

    /**
     * Get a user entity.
     *
     * @param string $username
     * @param string $password
     * @param string $grantType The grant type used
     * @param ClientEntityInterface $clientEntity
     *
     * @return UserEntityInterface|null
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $this->backendUser->setLoginData($username, $password, LoginType::LOGIN);
        $this->backendUser->checkAuthentication();
        if ($this->backendUser->user) {
            return GeneralUtility::makeInstance(Typo3BackendUser::class, $username, $this->backendUser->user);
        }

        return null;
    }
}
