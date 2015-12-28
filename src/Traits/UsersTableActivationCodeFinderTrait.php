<?php

namespace CrudUsers\Traits;

use CrudUsers\Datasource\Exception\UserNotFoundException;

trait UsersTableActivationCodeFinderTrait
{
    /**
     * {@inheritDoc}
     */
    public function getByActivationCode($activationCode)
    {
        $result = $this->findByActivationCode($activationCode);
        $count = $result->count();
        if ($count > 1) {
            throw new RuntimeException("Found [$count] users with the same activation code.");
        }

        if (!$count) {
            throw new UserNotFoundException("A user was not found with the given activation code.");
        }

        return $result->first();
    }
}
