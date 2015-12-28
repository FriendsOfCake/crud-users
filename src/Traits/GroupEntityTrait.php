<?php

namespace CrudUsers\Traits;

trait GroupEntityTrait
{
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}
