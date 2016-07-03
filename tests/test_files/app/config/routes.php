<?php

use Cake\Routing\Router;

Router::connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
