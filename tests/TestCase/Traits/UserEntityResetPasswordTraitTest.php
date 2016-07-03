<?php

namespace CrudUsers\Test\TestCase\Traits;

use Cake\TestSuite\TestCase;

class UserEntityResetPasswordTraitTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->Trait = $this->getMockForTrait(
            'CrudUsers\Traits\UserEntityResetPasswordTrait',
            [],
            '',
            true,
            true,
            true,
            ['_persist', 'randomizedString', 'getPasswordName']
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->Trait);
    }

    public function testAttemptResetPassword()
    {
        $this->Trait->reset_password_code = 'foo';

        $this->Trait->expects($this->once())
            ->method('getPasswordName')
            ->will($this->returnValue('password'));

        $this->Trait->expects($this->once())
            ->method('_persist')
            ->with([
                'password' => 'bar',
                'reset_password_code' => null
            ])
            ->will($this->returnValue('foobar'));

        $this->assertEquals('foobar', $this->Trait->attemptResetPassword('foo', 'bar'));
    }

    public function testCheckResetPasswordCode()
    {
        $this->Trait->reset_password_code = 'foo';
        $this->assertTrue($this->Trait->checkResetPasswordCode('foo'));
    }

    public function testClearResetPassword()
    {
        $this->Trait->reset_password_code = 'foo';

        $this->Trait->expects($this->once())
            ->method('_persist')
            ->with(['reset_password_code' => null]);

        $this->Trait->clearResetPassword();
    }

    public function testGetResetPasswordCode()
    {
        $this->Trait->expects($this->once())
            ->method('randomizedString')
            ->will($this->returnValue('bar'));

        $this->Trait->expects($this->once())
            ->method('_persist')
            ->with([
                'reset_password_code' => 'bar'
            ]);

        $this->assertEquals('bar', $this->Trait->getResetPasswordCode());
    }
}
