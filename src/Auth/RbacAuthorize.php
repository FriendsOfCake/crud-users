<?php

namespace CrudUsers\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Network\Request;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class RbacAuthorize extends BaseAuthorize
{
    protected $_defaultConfig = [
        'userModel' => 'CrudUsers.Users',
        'groupModel' => 'CrudUsers.Groups',
    ];

    public function authorize($user, Request $request)
    {
        $user = TableRegistry::get($this->config('userModel'))
            ->newEntity($user, [
                'associated' => [$this->config('groupModel')]
            ]);

        $route = Router::url(array_filter($request->params));
        $permission = preg_replace('~^/~', '', strtolower($route));
        return $user->hasAccess($permission);
    }
}
