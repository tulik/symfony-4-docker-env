<?php

declare(strict_types=1);

namespace App\Traits;

use App\Entity\User;

trait UserOwnableResourceTrait
{
    /**
     * @return User|null
     */
    abstract public function getUser(): ?User;

    public function isOwner($user): bool
    {
        if ($user instanceof User) {
            $owner = $this->getUser();

            return $owner->getId() === $user->getId();
        }

        return false;
    }
}
