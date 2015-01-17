<?php

namespace CrudUsers\Model\Table;

interface UsersTableInterface {

    /**
     * Finds a user by the given user ID.
     *
     * @param mixed $id
     * @return \CrudUsers\Model\Entity\UserInterface
     * @throws \CrudUsers\Datasource\Exception\UserNotFoundException
     */
    public function getById($id);

    /**
     * Finds a user by the login value.
     *
     * @param string $login
     * @return \CrudUsers\Model\Entity\UserInterface
     * @throws \CrudUsers\Datasource\Exception\UserNotFoundException
     */
    public function getByLogin($login);

    /**
     * Finds a user by the given activation code.
     *
     * @param string $code
     * @return \CrudUsers\Model\Entity\UserInterface
     * @throws \CrudUsers\Datasource\Exception\UserNotFoundException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function getByActivationCode($activationCode);

    /**
     * Finds a user by the given reset password code.
     *
     * @param string $code
     * @return \CrudUsers\Model\Entity\UserInterface
     * @throws \CrudUsers\Datasource\Exception\UserNotFoundException
     * @throws RuntimeException
     */
    public function getByResetPasswordCode($resetCode);
}
