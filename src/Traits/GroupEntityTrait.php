<?php

namespace CrudUsers\Traits;

trait GroupEntityTrait
{

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }
}
