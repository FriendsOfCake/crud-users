<?php

namespace CrudUsers\Test\TestCase\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\TestSuite\TestCase;
use CrudUsers\Model\Entity\User;

class UserTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->User = new User(['source' => 'Users']);
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->User);
    }

    public function testGetId()
    {
        $this->User->set(['user_id' => 1]);
        $expected = 1;
        $result = $this->User->getId();
        $this->assertEquals($expected, $result);
    }

    public function testGetLoginName()
    {
        $this->assertEquals('email', $this->User->getLoginName());
    }

    public function testGetLogin()
    {
        $this->User->email = 'john@doe.com';
        $this->assertEquals($this->User->email, $this->User->getLogin());
    }

    public function testGetPasswordName()
    {
        $this->assertEquals('password', $this->User->getPasswordName());
    }

    public function testGetPassword()
    {
        $hasher = $this->getMock('Cake\Auth\DefaultPasswordHasher', ['hash']);
        $this->User->setHasher($hasher);

        $hasher->expects($this->once())
            ->method('hash')
            ->with('foo')
            ->will($this->returnValue('bar'));

        $this->User->password = 'foo';

        $this->assertEquals('bar', $this->User->getPassword());
    }
}
