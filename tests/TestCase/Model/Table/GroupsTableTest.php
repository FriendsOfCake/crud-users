<?php

namespace CrudUsers\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CrudUsers\Model\Entity\Group;

class GroupsTableTest extends TestCase
{
    public $fixtures = [
        'plugin.crud_users.groups',
        'plugin.crud_users.users',
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Groups = TableRegistry::get('CrudUsers.Groups');
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->Groups);
    }

    public function testGetById()
    {
        $result = $this->Groups->getById(1);
        $this->assertInstanceOf('CrudUsers\Model\Entity\GroupInterface', $result);
        $this->assertEquals('administrator', $result->name);
    }

    /**
     * @expectedException \CrudUsers\Datasource\Exception\GroupNotFoundException
     */
    public function testGetByIdNonExistent()
    {
        $this->Groups->getById(999);
    }

    public function testGetByName()
    {
        $result = $this->Groups->getByName('administrator');
        $this->assertInstanceOf('CrudUsers\Model\Entity\GroupInterface', $result);
        $this->assertEquals(1, $result->id);
    }

    /**
     * @expectedException \CrudUsers\Datasource\Exception\GroupNotFoundException
     */
    public function testGetByNameNonExistent()
    {
        $this->Groups->getByName('foo');
    }
}
