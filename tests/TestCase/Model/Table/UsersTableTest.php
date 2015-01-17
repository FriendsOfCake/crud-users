<?php

namespace CrudUsers\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CrudUsers\Model\Entity\Group;

class UsersTableTest extends TestCase
{
    public $fixtures = [
        'plugin.crud_users.groups',
        'plugin.crud_users.users',
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Users = TableRegistry::get('CrudUsers.Users');
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->Users);
    }

    public function testGetById()
    {
        $result = $this->Users->getById(1);
        $this->assertInstanceOf('CrudUsers\Model\Entity\UserInterface', $result);
        $this->assertEquals('john@doe.com', $result->email);
    }

    /**
     * @expectedException \CrudUsers\Datasource\Exception\UserNotFoundException
     */
    public function testGetByIdNonExistent()
    {
        $this->Users->getById(999);
    }

    public function testGetByLogin()
    {
        $result = $this->Users->getByLogin('john@doe.com');
        $this->assertInstanceOf('CrudUsers\Model\Entity\UserInterface', $result);
        $this->assertEquals(1, $result->user_id);
    }

    /**
     * @expectedException \CrudUsers\Datasource\Exception\UserNotFoundException
     */
    public function testGetByLoginNonExistent()
    {
        $this->Users->getByLogin('foo');
    }
}
