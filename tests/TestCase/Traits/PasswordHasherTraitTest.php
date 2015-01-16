<?php

namespace CrudUsers\Test\TestCase\Traits;

use Cake\Auth\AbstractPasswordHasher;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use CrudUsers\Traits\PasswordHasherTrait;

class CustomPasswordHasher extends AbstractPasswordHasher
{
    public function check($password, $hashedPassword) {}
    public function hash($string) {}
}

class PasswordHasherUser extends Entity
{
    use PasswordHasherTrait;
}

class PasswordHasherTraitTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->Trait = $this->getMockForTrait('CrudUsers\Traits\PasswordHasherTrait');
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testGetHasher()
    {
        $this->assertInstanceOf('Cake\Auth\DefaultPasswordHasher', $this->Trait->getHasher());
    }

    public function testSetHasher()
    {
        $this->Trait->setHasher(new CustomPasswordHasher());
        $this->assertInstanceOf('CrudUsers\Test\TestCase\Traits\CustomPasswordHasher', $this->Trait->getHasher());
    }

    public function testSetPassword()
    {
        $hasher = $this->getMock('Cake\Auth\DefaultPasswordHasher', ['hash']);
        $hasher->expects($this->once())
            ->method('hash')
            ->with('foo')
            ->will($this->returnValue('bar'));

        $user = new PasswordHasherUser();
        $user->setHasher($hasher);

        $user->password = 'foo';
        $this->assertEquals('bar', $user->password);
    }
}
