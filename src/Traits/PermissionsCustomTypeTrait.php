<?php

namespace CrudUsers\Traits;

use Cake\Database\Schema\Table as Schema;

trait PermissionsCustomTypeTrait
{
    /**
     * {@inheritDoc}
     */
    protected function _initializeSchema(Schema $schema)
    {
        $schema->columnType('permissions', 'json');
        return $schema;
    }
}
