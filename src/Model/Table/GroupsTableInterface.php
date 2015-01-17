<?php

namespace CrudUsers\Model\Table;

interface GroupsTableInterface {

    /**
     * Find the group by ID.
     *
     * @param int $id
     * @return \CrudUsers\Model\Entity\GroupInterface  $group
     * @throws \CrudUsers\Datasource\Exception\GroupNotFoundException
     */
    public function getById($id);

    /**
     * Find the group by name.
     *
     * @param string $name
     * @return \CrudUsers\Model\Entity\GroupInterface  $group
     * @throws \CrudUsers\Datasource\Exception\GroupNotFoundException
     */
    public function getByName($name);
}
