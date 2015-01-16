<?php

namespace CrudUsers\Model\Table;

/**
 *
 *
 * Inspired by the cartalyst/sentry package (http://cartalyst.com)
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Cartalyst LLC
 * @link       http://cartalyst.com
 */

interface UsersTableInterface {

    /**
     * Finds a user by the given user ID.
     *
     * @param  mixed  $id
     * @return \CrudUsers\Model\Entity\UserInterface
     * @throws \CrudUsers\Datasource\Exception\UserNotFoundException
     */
    public function findById($id);

    /**
     * Finds a user by the login value.
     *
     * @param  string  $login
     * @return \CrudUsers\Model\Entity\UserInterface
     * @throws \CrudUsers\Datasource\Exception\UserNotFoundException
     */
    public function findByLogin($login);

    /**
     * Finds a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \CrudUsers\Model\Entity\UserInterface
     * @throws \CrudUsers\Datasource\Exception\UserNotFoundException
     */
    public function findByCredentials(array $credentials);

    /**
     * Finds a user by the given activation code.
     *
     * @param  string  $code
     * @return \CrudUsers\Model\Entity\UserInterface
     * @throws \CrudUsers\Datasource\Exception\UserNotFoundException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function findByActivationCode($code);

    /**
     * Finds a user by the given reset password code.
     *
     * @param  string  $code
     * @return \CrudUsers\Model\Entity\UserInterface
     * @throws \CrudUsers\Datasource\Exception\UserNotFoundException
     * @throws RuntimeException
     */
    public function findByResetPasswordCode($code);

    /**
     * Returns all users.
     *
     * @return \Cake\ORM\Query
     */
    public function findAll();

    /**
     * Returns all users who belong to
     * a group.
     *
     * @param  \CrudUsers\Model\Entity\GroupInterface  $group
     * @return \Cake\ORM\Query
     */
    public function findAllInGroup(GroupInterface $group);

    /**
     * Returns all users with access to
     * a permission(s).
     *
     * @param  string|array  $permissions
     * @return \Cake\ORM\Query
     */
    public function findAllWithAccess($permissions);

    /**
     * Returns all users with access to
     * any given permission(s).
     *
     * @param  array  $permissions
     * @return \Cake\ORM\Query
     */
    public function findAllWithAnyAccess(array $permissions);

    /**
     * Creates a user.
     *
     * @param  array  $credentials
     * @return \CrudUsers\Model\Entity\UserInterface
     */
    public function create(array $credentials);

    /**
     * Returns an empty user object.
     *
     * @return \CrudUsers\Model\Entity\UserInterface
     */
    public function getEmptyUser();
}
