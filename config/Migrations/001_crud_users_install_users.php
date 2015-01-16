<?php

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

class CrudUsersInstallUsers extends AbstractMigration
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table('users', ['id' => 'user_id'])

        ->addColumn('email', 'string', ['limit' => 254])
        ->addColumn('password', 'string', ['limit' => 255])
        ->addColumn('first_name', 'string', ['limit' => 35, 'null' => true])
        ->addColumn('last_name', 'string', ['limit' => 35, 'null' => true])
        ->addColumn('permissions', 'text')
        ->addColumn('is_active', 'boolean', ['default' => 0])
        ->addColumn('activation_code', 'string', ['null' => true])
        ->addColumn('reset_password_code', 'string', ['null' => true])
        ->addColumn('last_login', 'datetime')
        ->addColumn('activated', 'datetime')
        ->addColumn('created', 'datetime')
        ->addColumn('modified', 'datetime')

        ->addIndex(['email'], ['unique' => true])
        ->addIndex(['activation_code'])
        ->addIndex(['reset_password_code'])

        ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('users')->drop();
    }
}
