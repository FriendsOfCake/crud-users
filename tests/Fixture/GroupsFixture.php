<?php

namespace CrudUsers\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class GroupsFixture extends TestFixture
{

    public $fields = [
        'id' => ['type' => 'integer'],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false],
        'permissions' => ['type' => 'text'],
        'created' => ['type' => 'datetime'],
        'modified' => ['type' => 'datetime'],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ]
    ];

    public $records = [
        [
            'name' => 'administrator',
            'permissions' => []
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
