<?php

declare(strict_types=1);

namespace DFAU\ToujouOauth2Server\Domain\Entity;

interface UserRelatedClientEntityInterface
{
    public function getUserIdentifier(): string;
}
