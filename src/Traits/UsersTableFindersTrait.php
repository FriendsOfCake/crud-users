<?php

namespace CrudUsers\Traits;

use CrudUsers\Datasource\Exception\UserNotFoundException;

trait UsersTableFindersTrait
{
    /**
     * {@inheritDoc}
     */
    public function getById($id)
    {
        $conditions = [$this->primaryKey() => $id];
        $result = $this->find()->where($conditions);

        if (!$result->count()) {
            throw new UserNotFoundException("A user could not be found with ID [$id].");
        }

        return $result->first();
    }

    /**
     * {@inheritDoc}
     */
    public function getByLogin($login)
    {
        $conditions = [$this->newEntity()->getLoginName() => $login];
        $result = $this->find()->where($conditions);

        if (!$result->count()) {
            throw new UserNotFoundException("A user could not be found with a login value of [$login].");
        }

        return $result->first();
    }
}
