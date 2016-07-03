<?php

namespace CrudUsers\Model\Table;

use Cake\ORM\Table;
use CrudUsers\Traits\GroupsTableFindersTrait;
use CrudUsers\Traits\PermissionsCustomTypeTrait;

class GroupsTable extends Table implements GroupsTableInterface
{
    use GroupsTableFindersTrait;
    use PermissionsCustomTypeTrait;
}
