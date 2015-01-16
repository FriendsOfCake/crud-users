<?php

namespace CrudUsers\Traits;

trait UserEntityResetPasswordTrait
{

    use RandomStringTrait;

    public function attemptResetPassword($resetCode, $newPassword)
    {
        if ($this->checkResetPasswordCode($resetCode)) {
            return $this->_persist([
                $this->getPasswordName() => $newPassword,
                'reset_password_code' => null
            ]);
        }

        return false;
    }

    public function checkResetPasswordCode($resetCode)
    {
        return ($this->_properties['reset_password_code'] == $resetCode);
    }

    public function clearResetPassword()
    {
        if ($this->reset_password_code) {
            $this->_persist(['reset_password_code' => null]);
        }
    }

    public function getResetPasswordCode()
    {
        $this->_persist(['reset_password_code' => $this->randomizedString()]);
        return $this->reset_password_code;
    }

}
