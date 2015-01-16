<?php

namespace CrudUsers\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Network\Request;
use Cake\ORM\TableRegistry;

class RbacAuthorize extends BaseAuthorize
{
    protected $_defaultConfig = [
        'userModel' => 'CrudUsers.Users',
        'groupModel' => 'CrudUsers.Groups',
    ];

    public function authorize($user, Request $request)
    {
        $user = TableRegistry::get($this->config('userModel'))->newEntity($user);
        $permission = implode('/', $request->params());
        return $user->hasAccess($permission);
    }
}
