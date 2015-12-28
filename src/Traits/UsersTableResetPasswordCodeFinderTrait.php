<?php

namespace CrudUsers\Traits;

use CrudUsers\Datasource\Exception\UserNotFoundException;

trait UsersTableResetPasswordCodeFinderTrait
{
    /**
     * {@inheritDoc}
     */
    public function getByResetPasswordCode($resetCode)
    {
        $result = $this->findByResetPasswordCode($resetCode);
        $count = $result->count();
        if ($count > 1) {
            throw new RuntimeException("Found [$count] users with the same reset password code.");
        }

        if (!$count) {
            throw new UserNotFoundException("A user was not found with the given reset password code.");
        }

        return $result->first();
    }
}
