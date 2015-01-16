<?php

namespace CrudUsers\Test\TestCase\Traits;

use Cake\TestSuite\TestCase;

class UserEntityTraitTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->Trait = $this->getMockForTrait(
            'CrudUsers\Traits\UserEntityTrait',
            [],
            '',
            true,
            true,
            true,
            ['getHasher']
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->Trait);
    }

    public function testGetId()
    {
        $this->Trait->user_id = 1;
        $this->assertEquals(1, $this->Trait->getId());
    }

    public function testGetLogin()
    {
        $this->Trait->email = 'foo@bar.com';
        $this->assertEquals($this->Trait->email, $this->Trait->getLogin());
    }

    public function testGetLoginName()
    {
        $this->assertEquals('email', $this->Trait->getLoginName());
    }

    public function testGetPassword()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testGetPasswordName()
    {
        $this->assertEquals('password', $this->Trait->getPasswordName());
    }

    public function testGetPermissions()
    {
        $this->Trait->permissions = [];
        $this->assertEquals([], $this->Trait->getPermissions());
    }

    public function testRecordLogin()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
