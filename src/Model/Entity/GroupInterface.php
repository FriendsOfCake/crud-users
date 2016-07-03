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

interface GroupInterface
{
    /**
     * Returns the group's ID.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Returns the group's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns permissions for the group.
     *
     * @return array
     */
    public function getPermissions();
}
