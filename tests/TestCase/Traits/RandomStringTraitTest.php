<?php

namespace CrudUsers\Test\TestCase\Traits;

use Cake\TestSuite\TestCase;

class RandomStringTraitTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->Trait = $this->getMockForTrait('CrudUsers\Traits\RandomStringTrait');
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->Trait);
    }

    public function testRandomizedString()
    {
        $result = $this->Trait->randomizedString();
        $this->assertEquals(42, strlen($result));

        $result = $this->Trait->randomizedString(100);
        $this->assertEquals(100, strlen($result));
    }
}
