<?php

namespace CrudUsers\Model\Entity;

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

interface UserInterface
{
    /**
     * Returns the user's ID.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Returns the table's column name for the user's login.
     *
     * @return string
     */
    public function getLoginName();

    /**
     * Returns the user's login.
     *
     * @return string
     */
    public function getLogin();

    /**
     * Returns the table's column name for the user's password.
     *
     * @return string
     */
    public function getPasswordName();

    /**
     * Returns the user's password (hashed).
     *
     * @return string
     */
    public function getPassword();

    /**
     * Returns permissions for the user.
     *
     * @return array
     */
    public function getPermissions();

    /**
     * Check if the user is activated.
     *
     * @return bool
     */
    public function isActivated();

    /**
     * Checks if the user is a super user - has
     * access to everything regardless of permissions.
     *
     * @return bool
     */
    public function isSuperUser();

    /**
     * Get an activation code for the given user.
     *
     * @return string
     */
    public function getActivationCode();

    /**
     * Attempts to activate the given user by checking
     * the activate code. If the user is activated already,
     * an Exception is thrown.
     *
     * @param  string $activationCode an activation code
     * @return bool
     * @throws \CrudUsers\Datasource\Exception\UserAlreadyActivatedException
     */
    public function attemptActivation($activationCode);

    /**
     * Get a reset password code for the given user.
     *
     * @return string
     */
    public function getResetPasswordCode();

    /**
     * Checks if the provided user reset password code is
     * valid without actually resetting the password.
     *
     * @param  string $resetCode a reset code
     * @return bool
     */
    public function checkResetPasswordCode($resetCode);

    /**
     * Attempts to reset a user's password by matching
     * the reset code generated with the user's.
     *
     * @param  string $resetCode a reset code
     * @param  string $newPassword a new password
     * @return bool
     */
    public function attemptResetPassword($resetCode, $newPassword);

    /**
     * Wipes out the data associated with resetting
     * a password.
     *
     * @return void
     */
    public function clearResetPassword();

    /**
     * Returns an array of groups which the given
     * user belongs to.
     *
     * @return array
     */
    public function getGroups();

    /**
     * Adds the user to the given group
     *
     * @param  \CrudUser\Interfaces\GroupInterface $group a group entity
     * @return bool
     */
    public function addGroup(GroupInterface $group);

    /**
     * Removes the user from the given group.
     *
     * @param  \CrudUser\Interfaces\GroupInterface $group a group entity
     * @return bool
     */
    public function removeGroup(GroupInterface $group);

    /**
     * See if the user is in the given group.
     *
     * @param  \CrudUser\Interfaces\GroupInterface $group a group entity
     * @return bool
     */
    public function inGroup(GroupInterface $group);

    /**
     * Returns an array of merged permissions for each
     * group the user is in.
     *
     * @return array
     */
    public function getMergedPermissions();

    /**
     * See if a user has access to the passed permission(s).
     * Permissions are merged from all groups the user belongs to
     * and then are checked against the passed permission(s).
     *
     * If multiple permissions are passed, the user must
     * have access to all permissions passed through, unless the
     * "all" flag is set to false.
     *
     * Super users have access no matter what.
     *
     * @param string|array $permissions a string or list of permissions to check for
     * @param bool $all whether or not to require all permissions during a check
     * @return bool
     */
    public function hasAccess($permissions, $all = true);

    /**
     * See if a user has access to the passed permission(s).
     * Permissions are merged from all groups the user belongs to
     * and then are checked against the passed permission(s).
     *
     * If multiple permissions are passed, the user must
     * have access to all permissions passed through, unless the
     * "all" flag is set to false.
     *
     * Super users DON'T have access no matter what.
     *
     * @param string|array $permissions a string or list of permissions to check for
     * @param bool $all whether or not to require all permissions during a check
     * @return bool
     */
    public function hasPermission($permissions, $all = true);

    /**
     * Returns if the user has access to any of the
     * given permissions.
     *
     * @param  array  $permissions a list of permissions to check for
     * @return bool
     */
    public function hasAnyAccess(array $permissions);

    /**
     * Records a login for the user.
     *
     * @return void
     */
    public function recordLogin();
}
