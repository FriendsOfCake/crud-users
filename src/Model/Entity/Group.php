<?php

namespace CrudUsers\Model\Entity;

use Cake\ORM\Entity;
use CrudUsers\Traits\GroupEntityTrait;

class Group extends Entity implements GroupInterface
{
    use GroupEntityTrait;
}
