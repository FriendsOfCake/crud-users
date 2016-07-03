<?php

namespace CrudUsers\Traits;

use Cake\Auth\AbstractPasswordHasher;
use Cake\Auth\DefaultPasswordHasher;

trait PasswordHasherTrait
{
    protected static $_hasher;

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

    protected function _setPassword($password)
    {
        return $password === null ? null : $this->getHasher()->hash($password);
    }
}
