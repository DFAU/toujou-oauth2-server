<?php

declare(strict_types=1);

namespace DFAU\ToujouOauth2Server\Authentication;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Authentication\LoginType;

class Oauth2BackendUserAuthentication extends BackendUserAuthentication
{
    /** @var string */
    protected $clientIdentifier;

    /** @var string */
    protected $clientSecret;

    /** @var string */
    protected $clientLoginType;

    public function __construct()
    {
        $this->id = '';
        parent::__construct();
    }

    public function setLoginData(string $clientIdentifier, string $clientSecret, string $clientLoginType): void
    {
        $this->clientIdentifier = $clientIdentifier;
        $this->clientSecret = $clientSecret;
        $this->clientLoginType = $clientLoginType;
    }

    public function getLoginFormData(): array
    {
        $loginData = [
            'uname' => $this->clientIdentifier,
            'uident' => $this->clientSecret,
            'status' => $this->clientLoginType,
        ];

        // Only process the login data if a login is requested
        if (LoginType::LOGIN === $loginData['status']) {
            $loginData = $this->processLoginData($loginData, 'normal');
        }

        return \array_map('trim', $loginData);
    }
}
