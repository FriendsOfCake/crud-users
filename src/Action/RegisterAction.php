<?php

namespace CrudUsers\Action;

use Crud\Action\BaseAction;
use Crud\Event\Subject;
use Crud\Traits\RedirectTrait;
use Crud\Traits\SaveMethodTrait;
use Crud\Traits\ViewTrait;
use Crud\Traits\ViewVarTrait;

class RegisterAction extends BaseAction
{

    use RedirectTrait;
    use SaveMethodTrait;
    use ViewTrait;
    use ViewVarTrait;

    protected $_defaultConfig = [
        'enabled' => true,
        'fields' => null,
        'scope' => 'entity',
        'inflection' => 'singular',
        'relatedModels' => true,
        'saveMethod' => 'save',
        'saveOptions' => [],
        'view' => null,
        'viewVar' => null,
        'entityKey' => 'entity',
        'messages' => [
            'success' => [
                'text' => 'Account successfully created'
            ],
            'error' => [
                'text' => 'Please fix the errors and try again'
            ]
        ],
    ];

    /**
     * HTTP GET handler
     *
     * @return void
     */
    protected function _get()
    {
        $subject = $this->_subject([
            'success' => true,
            'entity' => $this->_entity($this->_request()->query ?: null, $this->saveOptions())
        ]);

        $this->_trigger('beforeRender', $subject);
    }

    /**
     * HTTP POST handler
     *
     * @return void|\Cake\Network\Response
     */
    protected function _post()
    {
        $data = $this->_request()->data;

        if ($this->config('fields')) {
            $whitelist = array_flip($this->config('fields');
            $data = array_intersect_key($this->_request()->data, $whitelist));
        }

        $subject = $this->_subject([
            'entity' => $this->_entity($data, $this->saveOptions()),
            'saveMethod' => $this->saveMethod(),
            'saveOptions' => $this->saveOptions()
        ]);

        $this->_trigger('beforeRegister', $subject);

        $saveCallback = [$this->_table(), $subject->saveMethod];
        if ($saveCallback($subject->entity, $subject->saveOptions)) {
            return $this->_success($subject);
        }

        return $this->_error($subject);
    }

    /**
     * Post success callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return \Cake\Network\Response
     */
    protected function _success(Subject $subject)
    {
        $subject->set(['success' => true, 'created' => true]);

        $this->_trigger('afterRegister', $subject);
        $this->setFlash('success', $subject);

        return $this->_redirect($subject, '/');
    }

    /**
     * Post error callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return void
     */
    protected function _error(Subject $subject)
    {
        $subject->set(['success' => false, 'created' => true]);

        $this->_trigger('afterRegister', $subject);
        $this->setFlash('error', $subject);
        $this->_trigger('beforeRender', $subject);
    }
}
