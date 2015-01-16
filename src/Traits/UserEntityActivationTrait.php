<?php

namespace CrudUsers\Traits;

trait UserEntityActivationTrait
{

    public function attemptActivation($activationCode)
    {
        if ($this->isActivated()) {
            throw new UserAlreadyActivatedException('Cannot attempt activation on an already activated user.');
        }

        if ($activationCode == $this->activation_code) {
            return $this->_persist([
                'activation_code' => null,
                'is_active' => true,
            ]);
        }

        return false;
    }

    public function getActivationCode()
    {
        $activationCode = $this->randomizedString();
        $this->_persist(['activation_code' => $activationCode]);
        return $activationCode;
    }

    public function isActivated()
    {
        return $this->is_active;
    }

}
