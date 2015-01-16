<?php

namespace CrudUsers\Model\Entity;

use Cake\ORM\Entity;

class User extends Entity implements UserInterface
{
    protected static $_loginAttribute = 'email';

    protected $_accessible = [
        '*' => true,
        'reset_password_code' => false,
        'activation_code' => false,
    ];

    protected $_hidden = [
        'password',
        'reset_password_code',
        'activation_code'
    ];

    public function getId()
    {
        return $this->user_id;
    }

    public function getLoginName()
    {
        return self::$_loginAttribute;
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

    public function getActivationCode()
    {
        $this->activation_code = $this->getRandomString();

        (TableRegistry::get($this->source()))->save($this);

        return $this->activation_code;
    }

    public function getRandomString($length = 42)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length * 2);
            if (false === $bytes) {
                throw new RuntimeException('Unable to generate random string.');
            }
            return substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length);
        }

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function isSuperUser()
    {
        return $this->hasPermission('superuser');
    }

    public function checkResetPasswordCode($resetCode)
    {
        return ($this->_properties['reset_password_code'] == $resetCode);
    }

    public function attemptResetPassword($resetCode, $newPassword)
    {
        if ($this->checkResetPasswordCode($resetCode)) {
            $this->{$this->getPasswordName()} = $newPassword;
            $this->reset_password_code = null;
            return (TableRegistry::get($this->source()))->save($this);
        }

        return false;
    }

    public function clearResetPassword()
    {
        if ($this->reset_password_code) {
            $this->reset_password_code = null;
            (TableRegistry::get($this->source()))->save($this);
        }
    }

    public function getHasher()
    {
        return static::$_hasher;
    }

    public function setHasher(AbstractPasswordHasher $hasher)
    {
        static::$_hasher = $hasher;
    }

    public function _setPermissions(array $permissions)
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
        return (new $this->getHasher())->hash($password);
    }
}
