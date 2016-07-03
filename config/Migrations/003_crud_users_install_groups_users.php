<?php

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

class CrudUsersInstallGroupsUsers extends AbstractMigration
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table('groups_users', ['primary_key' => ['group_id', 'user_id']])

        ->addColumn('group_id', 'integer', ['limit' => 11])
        ->addColumn('user_id', 'integer', ['limit' => 11])

        ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('groups_users')->drop();
    }
}
