<?php

namespace CrudUsers\Test\TestCase\Traits;

use CrudUsers\Model\Entity\Group;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;

class UserEntityMembershipTraitTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->Trait = $this->getMockForTrait(
            'CrudUsers\Traits\UserEntityMembershipTrait',
            [],
            '',
            true,
            true,
            true,
            ['_persist', 'getPermissions']
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->Trait);
    }

    public function testGetGroups()
    {
        $this->Trait->groups = [];
        $this->assertEquals([], $this->Trait->getGroups());
    }

    public function testAddGroup()
    {
        $group = new Group(['id' => 1, 'permissions' => ['superuser' => 1]]);
        $this->Trait->groups = [];
        $this->Trait->expects($this->once())->method('_persist');
        $this->Trait->addGroup($group);
        $this->assertEquals([$group], $this->Trait->groups);
    }

    public function testRemoveGroup()
    {
        $group = new Group(['id' => 1, 'permissions' => ['superuser' => 1]]);
        $this->Trait->groups = [$group];
        $this->Trait->expects($this->once())->method('_persist');
        $this->Trait->removeGroup($group);
        $this->assertEquals([], $this->Trait->groups);
    }

    public function testInGroup()
    {
        $this->Trait->groups = [
            new Group(['id' => 3]),
            new Group(['id' => 5]),
        ];

        $inGroup = new Group(['id' => 3]);
        $notInGroup = new Group(['id' => 1]);

        $this->assertTrue($this->Trait->inGroup($inGroup));
        $this->assertFalse($this->Trait->inGroup($notInGroup));
    }

    public function testHasAccess()
    {
        $this->Trait->groups = [
            new Group(['id' => 1, 'permissions' => ['member/add' => 1, 'member/edit' => 1, 'member/delete' => 1]]),
        ];

        $this->Trait->expects($this->once())
            ->method('getPermissions')
            ->will($this->returnValue(['ownedBy/2' => 1]));

        $this->assertTrue($this->Trait->hasAccess(['*/delete', 'ownedBy/2']));
    }

    public function testHasPermission()
    {
        $this->Trait->groups = [
            new Group(['id' => 1, 'permissions' => ['reports/expenses' => 1, 'reports/sales' => 1]])
        ];

        $this->Trait->expects($this->once())
            ->method('getPermissions')
            ->will($this->returnValue([]));

        $this->assertTrue($this->Trait->hasPermission('reports/expenses'));
    }

    public function testHasAnyAccess()
    {
        $this->Trait->groups = [
            new Group(['id' => 1, 'permissions' => ['admin/add' => 1, 'admin/edit' => 1, 'admin/delete' => 0]]),
            new Group(['id' => 2, 'permissions' => ['admin/delete' => 1]])
        ];

        $this->Trait->expects($this->once())
            ->method('getPermissions')
            ->will($this->returnValue([]));

        $this->assertTrue($this->Trait->hasAnyAccess(['admin/delete', 'moderator/delete']));
    }

    public function testGetMergedPermissions()
    {
        $this->Trait->groups = [
            new Group(['id' => 1, 'permissions' => ['admin/add' => 1, 'admin/edit' => 1, 'admin/delete' => 0]]),
            new Group(['id' => 2, 'permissions' => ['admin/delete' => 1]])
        ];

        $this->Trait->expects($this->once())
            ->method('getPermissions')
            ->will($this->returnValue([]));

        $expected = [
            'admin/add' => 1,
            'admin/edit' => 1,
            'admin/delete' => 1,
        ];

        $this->assertEquals($expected, $this->Trait->getMergedPermissions());
    }

    public function testIsSuperUser()
    {
        $this->Trait->groups = [];

        $this->Trait->expects($this->once())
            ->method('getPermissions')
            ->will($this->returnValue(['superuser' => 1]));

        $this->assertTrue($this->Trait->isSuperUser());
    }

    public function testSetPermissions()
    {
        $user = $this->getMock(
            'CrudUsers\Model\Entity\User',
            ['getPermissions'],
            [['permissions' => []]]
        );

        $user->expects($this->once())
            ->method('getPermissions')
            ->will($this->returnValue([]));

        $user->permissions = [
            'admin/edit' => 1,
            'admin/delete' => -1,
            'admin/add' => 0,
        ];

        $this->assertEquals(['admin/edit' => 1, 'admin/delete' => -1], $user->permissions);
    }
}
