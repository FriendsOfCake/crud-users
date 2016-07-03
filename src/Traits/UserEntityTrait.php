<?php

namespace CrudUsers\Traits;

trait UserEntityTrait
{

    use PasswordHasherTrait;
    use PersistentEntityTrait;
    use RandomStringTrait;
    use UserEntityActivationTrait;
    use UserEntityMembershipTrait;
    use UserEntityResetPasswordTrait;

    protected static $_loginAttribute = 'email';

    public function getId()
    {
        return $this->user_id;
    }

    public function getLogin()
    {
        return $this->{$this->getLoginName()};
    }

    public function getLoginName()
    {
        return self::$_loginAttribute;
    }

    public function getPassword()
    {
        return $this->{$this->getPasswordName()};
    }

    public function getPasswordName()
    {
        return 'password';
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function recordLogin()
    {
    }
}
