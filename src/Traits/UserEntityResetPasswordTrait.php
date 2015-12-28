<?php

namespace CrudUsers\Traits;

trait UserEntityResetPasswordTrait
{
    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function checkResetPasswordCode($resetCode)
    {
        return $this->reset_password_code == $resetCode;
    }

    /**
     * {@inheritDoc}
     */
    public function clearResetPassword()
    {
        if ($this->reset_password_code) {
            $this->_persist(['reset_password_code' => null]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getResetPasswordCode()
    {
        $resetCode = $this->randomizedString();
        $this->_persist(['reset_password_code' => $resetCode]);
        return $resetCode;
    }
}
