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
     * @return void|\Cake\Network\Response
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
        $subject = $this->_subject([
            'entity' => $this->_entity($this->_request()->data, $this->saveOptions()),
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
     * @param array Authenticated user record data.
     * @return \Cake\Network\Response
     */
    protected function _success(Subject $subject, array $user)
    {
        $subject->set(['success' => true, 'created' => true]);

        $this->_trigger('afterRegister', $subject);
        $this->setFlash('success', $subject);

        return $this->_redirect($subject, ['controller' => 'pages', 'action' => 'home']);
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
