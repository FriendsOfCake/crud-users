<?php

namespace CrudUsers\Traits;

use Cake\Auth\AbstractPasswordHasher;
use Cake\Auth\DefaultPasswordHasher;
use CrudUsers\Model\Entity\GroupInterface;
use InvalidArgumentException;
use RuntimeException;

trait UserEntityTrait
{
    use PersistentEntityTrait;
    use UserEntityActivationTrait;
    use UserEntityResetPasswordTrait;

    protected static $_loginAttribute = 'email';
    protected static $_hasher;

    public function getId()
    {
        return $this->user_id;
    }

    public function getLoginName()
    {
        return self::$_loginAttribute;
    }

    public function getLogin()
    {
        return $this->{$this->getLoginName()};
    }

    public function getPasswordName()
    {
        return 'password';
    }

    public function getPassword()
    {
        return $this->{$this->getPasswordName()};
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function isSuperUser()
    {
        return $this->hasPermission('superuser');
    }

    public function getHasher()
    {
        if (empty(static::$_hasher)) {
            $this->setHasher(new DefaultPasswordHasher());
        }

        return static::$_hasher;
    }

    public function setHasher(AbstractPasswordHasher $hasher)
    {
        static::$_hasher = $hasher;
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

    protected function _setPassword($password)
    {
        return $this->getHasher()->hash($password);
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

    public function recordLogin()
    {
    }
}
