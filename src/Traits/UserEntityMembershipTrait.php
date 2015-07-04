<?php

namespace CrudUsers\Traits;

use Cake\Collection\Collection;
use CrudUsers\Model\Entity\GroupInterface;
use InvalidArgumentException;

trait UserEntityMembershipTrait
{
    public $mergedPermissions = [];

    protected $_allowedPermissionsValues = [-1, 0, 1];

    public function isSuperUser()
    {
        return $this->hasPermission('superuser');
    }

    protected function _setPermissions(array $permissions)
    {
        $permissions = array_merge((array)$this->getPermissions(), $permissions);

        foreach ($permissions as $permission => &$value) {
            $value = (int)$value;

            if (!in_array($value, $this->_allowedPermissionsValues)) {
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
        return $this->groups;
    }

    public function addGroup(GroupInterface $group)
    {
        if (!$this->inGroup($group)) {
            array_push($this->groups, $group);
            $this->_persist();
        }
    }

    public function removeGroup(GroupInterface $group)
    {
        if ($this->inGroup($group)) {
            $groups = new Collection($this->getGroups());
            $this->groups = $groups->filter(function($g) use ($group) {
                return $g->getId() !== $group->getId();
            })->toArray();
            $this->_persist();
        }
    }

    public function inGroup(GroupInterface $group)
    {
        $groups = new Collection($this->getGroups());
        return (bool)count($groups->match(['id' => $group->getId()])->toArray());
    }

    public function hasAccess($permissions, $all = true)
    {
        return $this->isSuperUser() || $this->hasPermission($permissions, $all);
    }

    public function hasPermission($permissions, $all = true)
    {
        $mergedPermissions = $this->getMergedPermissions();

        foreach ((array)$permissions as $permission) {
            $endsWithWildcard = strlen($permission) > 1 && substr($permission, -1) == '*';
            $startsWithWildcard = strlen($permission) > 1 && substr($permission, 0, 1) == '*';
            $matched = true;

            if ($endsWithWildcard) {
                $matched = false;
                foreach ($mergedPermissions as $mergedPermission => $value) {
                    $checkPermission = substr($permission, 0, -1);
                    if (
                        $checkPermission != $mergedPermission
                        && substr($mergedPermission, 0, strlen($checkPermission)) === $checkPermission
                        && $value == 1
                    ) {
                        $matched = true;
                        break;
                    }
                }
            } else if ($startsWithWildcard) {
                $matched = false;
                foreach ($mergedPermissions as $mergedPermission => $value) {
                    $checkPermission = substr($permission, 1);
                    if (
                        $checkPermission != $mergedPermission
                        && substr($mergedPermission, -(strlen($checkPermission))) === $checkPermission
                        && $value == 1
                    ) {
                        $matched = true;
                        break;
                    }
                }
            } else {
                $matched = false;
                foreach ($mergedPermissions as $mergedPermission => $value) {
                    if (strlen($mergedPermission) >= 1 && substr($mergedPermission, -1) == '*') {
                        $matched = false;
                        $checkMergedPermission = substr($mergedPermission, 0, -1);
                        if (
                            $checkMergedPermission != $permission
                            && substr($permission, 0, strlen($checkMergedPermission)) == $checkMergedPermission
                            && $value == 1
                        ) {
                            $matched = true;
                            break;
                        }
                    } else if (strlen($mergedPermission) > 1 && substr($mergedPermission, 0, 1) == '*') {
                        $matched = false;
                        $checkMergedPermission = substr($mergedPermission, 1);
                        if (
                            $checkMergedPermission != $permission
                            && substr($permission, -(strlen($checkMergedPermission))) == $checkedMergedPermission
                            && $value == 1
                        ) {
                            $matched = true;
                            break;
                        }
                    } else if (
                        $permission == $mergedPermission
                        && !empty($mergedPermissions[$permission])
                    ) {
                        $matched = true;
                        break;
                    }
                }
            }

            if ($all && !$matched) {
                return false;
            } else if (!$all && $matched) {
                return true;
            }
        }

        return $all;
    }

    public function hasAnyAccess(array $permissions)
    {
        return $this->hasAccess($permissions, false);
    }

    public function getMergedPermissions()
    {
        if (!$this->mergedPermissions) {
            $permissions = [];
            foreach ($this->getGroups() as $group) {
                $permissions = array_merge($permissions, $group->getPermissions());
            }
            $this->mergedPermissions = array_merge($permissions, (array)$this->getPermissions());
            if (!empty($this->permissions)) {
                $this->mergedPermissions = array_merge($this->mergedPermissions, (array)$this->permissions);
            }
        }
        return $this->mergedPermissions;
    }
}
