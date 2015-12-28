<?php

namespace CrudUsers\Model\Table;

use Cake\ORM\Table;
use CrudUsers\Traits\PermissionsCustomTypeTrait;
use CrudUsers\Traits\UsersTableActivationCodeFinderTrait;
use CrudUsers\Traits\UsersTableFindersTrait;
use CrudUsers\Traits\UsersTableResetPasswordCodeFinderTrait;

class UsersTable extends Table implements UsersTableInterface
{
    use PermissionsCustomTypeTrait;
    use UsersTableActivationCodeFinderTrait;
    use UsersTableFindersTrait;
    use UsersTableResetPasswordCodeFinderTrait;

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
        $this->primaryKey('user_id');
    }
}
