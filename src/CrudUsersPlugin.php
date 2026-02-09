<?php
declare(strict_types=1);

namespace Marketing;

use Cake\Core\BasePlugin;

class CrudUsersPlugin extends BasePlugin
{
    protected ?string $name = 'CrudUsers';

    protected bool $bootstrapEnabled = true;

    protected bool $consoleEnabled = true;

    protected bool $middlewareEnabled = true;

    protected bool $servicesEnabled = true;

    protected bool $routesEnabled = true;

    protected bool $eventsEnabled = true;
}
