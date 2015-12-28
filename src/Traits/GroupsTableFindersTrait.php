<?php

namespace CrudUsers\Traits;

use CrudUsers\Datasource\Exception\GroupNotFoundException;

trait GroupsTableFindersTrait
{
    /**
     * {@inheritDoc}
     */
    public function getById($id)
    {
        $result = $this->findById($id);

        if (!$result->count()) {
            throw new GroupNotFoundException("A group could not be found with ID [$id].");
        }

        return $result->first();
    }

    /**
     * {@inheritDoc}
     */
    public function getByName($name)
    {
        $result = $this->findByName($name);

        if (!$result->count()) {
            throw new GroupNotFoundException("A group could not be found with the name [$name].");
        }

        return $result->first();
    }
}
