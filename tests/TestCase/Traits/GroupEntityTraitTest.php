<?php

namespace CrudUsers\Test\TestCase\Traits;

use Cake\TestSuite\TestCase;

class GroupEntityTraitTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->Trait = $this->getMockForTrait('CrudUsers\Traits\GroupEntityTrait');
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->Trait);
    }

    public function testGetId()
    {
        $this->Trait->id = 1;
        $this->assertEquals(1, $this->Trait->getId());
    }

    public function testGetName()
    {
        $this->Trait->name = 'foo';
        $this->assertEquals('foo', $this->Trait->getName());
    }

    public function testGetPermissions()
    {
        $this->Trait->permissions = [];
        $this->assertEquals([], $this->Trait->getPermissions());
    }
}
