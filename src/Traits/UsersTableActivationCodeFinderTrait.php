<?php

namespace CrudUsers\Traits;

use CrudUsers\Datasource\Exception\UserNotFoundException;

trait UsersTableActivationCodeFinderTrait
{
    public function getByActivationCode($activationCode)
    {
        $result = $this->findByActivationCode($activationCode);

        if ($result->count() > 1) {
            throw new RuntimeException("Found [$count] users with the same activation code.");
        }

        if (!$result->count()) {
            throw new UserNotFoundException("A user was not found with the given activation code.");
        }

        return $result->first();
    }
}
