<?php

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

class CrudUsersInstallGroups extends AbstractMigration
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table('groups')

        ->addColumn('name', 'string')
        ->addColumn('permissions', 'text')
        ->addColumn('created', 'datetime')
        ->addColumn('modified', 'datetime')

        ->addIndex(['name'], ['unique' => true])

        ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('groups')->drop();
    }
}
