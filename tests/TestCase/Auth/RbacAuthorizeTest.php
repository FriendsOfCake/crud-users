<?php

namespace CrudUsers\Test\TestCase\Auth;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CrudUsers\Auth\RbacAuthorize;

class RbacAuthorizeTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->Collection = $this->getMock('Cake\Controller\ComponentRegistry');
        $this->auth = new RbacAuthorize($this->Collection);
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->auth, $this->Collection);
    }

    public function testAuthorize()
    {
        $authUser = [
            'id' => 1,
            'permissions' => [],
            'groups' => [
                ['id' => 1, 'permissions' => ['foo/bar']]
            ]
        ];

        $user = $this->getMock('CrudUsers\Model\Entity\User', ['hasAccess'], [$authUser]);

        $user->expects($this->once())
            ->method('hasAccess')
            ->will($this->returnValue(true));

        $users = $this->getMock('Cake\ORM\Table', ['newEntity']);
        TableRegistry::set('CrudUsers.Users', $users);

        $users->expects($this->once())
            ->method('newEntity')
            ->with($authUser)
            ->will($this->returnValue($user));

        $request = $this->getMock('Cake\Network\Request', ['params']);

        $request->expects($this->once())
            ->method('params')
            ->will($this->returnValue([
                'plugin' => null,
                'controller' => 'foo',
                'action' => 'bar'
            ]));

        $this->assertTrue($this->auth->authorize($authUser, $request));
    }
}
