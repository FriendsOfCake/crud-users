<?php

namespace CrudUsers\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Network\Request;
use Cake\ORM\TableRegistry;
use Cake\Routing\Exception\MissingRouteException;
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

        $params = array_filter($request->params, function ($item) {
            return !empty($item) && is_string($item);
        });
        $permissions = [];

        $route = $this->_getRouteMatch($params);
        $permissions[] = preg_replace('~^/~', '', strtolower($route));

        while (count($params)) {
            $permissions[] = strtolower(implode('/', $params));
            array_pop($params);
        }

        return $user->hasAnyAccess(array_unique($permissions));
    }

    protected function _getRouteMatch(array $params)
    {
        try {
            $route = Router::url($params);
        } catch (MissingRouteException $e) {
            if (!empty($params['_method'])) {
                throw new MissingRouteException($e->getMessage());
            }
        }

        foreach (['DELETE', 'PUT', 'POST', 'GET'] as $method) {
            try {
                $route = Router::url($params + ['_method' => $method]);
                break;
            } catch (MissingRouteException $e) {
            }
        }

        if (empty($route)) {
            throw new MissingRouteException($e->getMessage());
        }

        return $route;
    }
}
