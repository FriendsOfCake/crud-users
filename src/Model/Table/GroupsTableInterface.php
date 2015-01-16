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

interface GroupsTableInterface {

    /**
     * Find the group by ID.
     *
     * @param  int  $id
     * @return \CrudUsers\Model\Entity\GroupInterface  $group
     * @throws \CrudUsers\Datasource\Exception\GroupNotFoundException
     */
    public function findById($id);

    /**
     * Find the group by name.
     *
     * @param  string  $name
     * @return \CrudUsers\Model\Entity\GroupInterface  $group
     * @throws \CrudUsers\Datasource\Exception\GroupNotFoundException
     */
    public function findByName($name);

    /**
     * Returns all groups.
     *
     * @return \Cake\ORM\Query
     */
    public function findAll();
}
