<?php

namespace CrudUsers\Model\Table;

interface GroupsTableInterface
{
    /**
     * Find the group by ID.
     *
     * @param int $id the group id
     * @return \CrudUsers\Model\Entity\GroupInterface  $group
     * @throws \CrudUsers\Datasource\Exception\GroupNotFoundException
     */
    public function getById($id);

    /**
     * Find the group by name.
     *
     * @param string $name the name of the group
     * @return \CrudUsers\Model\Entity\GroupInterface $group
     * @throws \CrudUsers\Datasource\Exception\GroupNotFoundException
     */
    public function getByName($name);
}
