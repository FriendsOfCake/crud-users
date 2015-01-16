<?php

namespace CrudUsers\Traits;

use Cake\ORM\TableRegistry;

trait PersistentEntityTrait
{
    protected function _persist(array $data)
    {
        $this->set($data);
        return TableRegistry::get($this->source())->save($this);
    }
}
