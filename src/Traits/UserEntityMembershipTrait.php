<?php

namespace CrudUsers\Traits;

use CrudUsers\Model\Entity\GroupInterface;
use InvalidArgumentException;

trait UserEntityMembershipTrait
{
    public function isSuperUser()
    {
        return $this->hasPermission('superuser');
    }

    protected function _setPermissions(array $permissions)
    {
        $permissions = array_merge($this->getPermissions(), $permissions);

        foreach ($permissions as $permission => &$value) {
            if (!in_array($value = (int)$value, $this->allowedPermissionsValues)) {
                throw new InvalidArgumentException(sprintf(
                    'Invalid value [%s] for permissions [%s] given.',
                    $value,
                    $permission
                ));
            }

            if (0 === $value) {
                unset($permissions[$permission]);
            }
        }

        return $permissions;
    }

    public function getGroups()
    {
    }

    public function addGroup(GroupInterface $group)
    {
    }

    public function removeGroup(GroupInterface $group)
    {
    }

    public function inGroup(GroupInterface $group)
    {
    }

    public function hasAccess($permissions, $all = true)
    {
    }

    public function hasPermission($permissions, $all = true)
    {
    }

    public function hasAnyAccess(array $permissions)
    {
    }

    public function getMergedPermissions()
    {
    }
}
