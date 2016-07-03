<?php

namespace CrudUsers\Test\TestCase\Traits;

use Cake\TestSuite\TestCase;

class UserEntityActivationTraitTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->Trait = $this->getMockForTrait(
            'CrudUsers\Traits\UserEntityActivationTrait',
            [],
            '',
            true,
            true,
            true,
            ['_persist', 'randomizedString']
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->Trait);
    }

    public function testAttemptActivation()
    {
        $this->Trait->activation_code = 'foo';
        $this->Trait->is_active = false;

        $this->Trait->expects($this->once())
            ->method('_persist')
            ->with([
                'activation_code' => null,
                'is_active' => true
            ])
            ->will($this->returnValue('foobar'));

        $this->assertEquals('foobar', $this->Trait->attemptActivation('foo', 'bar'));
    }

    public function testGetActivationCode()
    {
        $this->Trait->expects($this->once())
            ->method('randomizedString')
            ->will($this->returnValue('bar'));

        $this->Trait->expects($this->once())
            ->method('_persist')
            ->with([
                'activation_code' => 'bar'
            ]);

        $this->assertEquals('bar', $this->Trait->getActivationCode());
    }

    public function testIsActivated()
    {
        $this->Trait->is_active = true;
        $this->assertTrue($this->Trait->isActivated());

        $this->Trait->is_active = false;
        $this->assertFalse($this->Trait->isActivated());
    }
}
