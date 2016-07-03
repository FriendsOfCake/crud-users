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
        TableRegistry::clear();
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

        $request = $this->getMock('Cake\Network\Request');
        $request->params = ['controller' => 'Pages', 'action' => 'display', 'home'];

        $user = $this->getMock('CrudUsers\Model\Entity\User', ['hasAccess'], [$authUser]);
        $user->expects($this->once())
            ->method('hasAccess')
            ->with()
            ->will($this->returnValue(true));

        $users = $this->getMock('Cake\ORM\Table', ['newEntity']);
        $users->expects($this->once())
            ->method('newEntity')
            ->with($authUser, ['associated' => ['CrudUsers.Groups']])
            ->will($this->returnValue($user));

        TableRegistry::set('CrudUsers.Users', $users);

        $this->assertTrue($this->auth->authorize($authUser, $request));
    }
}
