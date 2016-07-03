<?php

namespace CrudUsers\Model\Entity;

use Cake\ORM\Entity;
use CrudUsers\Traits\UserEntityTrait;

class User extends Entity implements UserInterface
{
    use UserEntityTrait;

    protected $_accessible = [
        '*' => true,
        'reset_password_code' => false,
        'activation_code' => false,
    ];

    protected $_hidden = [
        'password',
        'reset_password_code',
        'activation_code'
    ];
}
