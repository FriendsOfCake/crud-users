<?php

namespace CrudUsers\Model\Entity;

use Cake\ORM\Entity;

class Group extends Entity implements GroupInterface
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
