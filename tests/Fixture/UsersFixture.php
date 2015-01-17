<?php

namespace CrudUsers\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture
{

    public $fields = [
        'user_id' => ['type' => 'integer'],
        'email' => ['type' => 'string', 'length' => 254, 'null' => false],
        'password' => ['type' => 'string', 'length' => 255, 'null' => false],
        'first_name' => ['type' => 'string', 'length' => 35],
        'last_name' => ['type' => 'string', 'length' => 35],
        'permissions' => ['type' => 'text'],
        'is_active' => ['type' => 'boolean', 'default' => false, 'null' => false],
        'activation_code' => ['type' => 'string', 'length' => 255],
        'reset_password_code' => ['type' => 'string', 'length' => 255],
        'last_login' => ['type' => 'datetime'],
        'activated' => ['type' => 'datetime'],
        'created' => ['type' => 'datetime'],
        'modified' => ['type' => 'datetime'],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['user_id']],
            'UNIQUE_EMAIL' => ['type' => 'unique', 'columns' => ['email']]
        ]
    ];

    public $records = [
        [
            'user_id' => 1,
            'email' => 'john@doe.com',
            'password' => '',
            'first_name' => null,
            'last_name' => null,
            'permissions' => [],
            'is_active' => 1,
            'activation_code' => null,
            'reset_password_code' => null,
        ]
    ];

    public function init()
    {
        foreach ($this->records as &$record)
        {
            $record['permissions'] = json_encode($record['permissions']);
        }

        return parent::init();
    }
}
