<?php

namespace CrudUsers\Traits;

use CrudUsers\Datasource\Exception\UserNotFoundException;

trait UsersTableResetPasswordCodeFinderTrait
{
    public function getByResetPasswordCode($resetCode)
    {
        $result = $this->findByResetPasswordCode($resetCode);

        if ($result->count() > 1) {
            throw new RuntimeException("Found [$count] users with the same reset password code.");
        }

        if (!$result->count()) {
            throw new UserNotFoundException("A user was not found with the given reset password code.");
        }

        return $result->first();
    }
}
