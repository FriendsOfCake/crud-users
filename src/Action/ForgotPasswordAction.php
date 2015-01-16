<?php

namespace Crud\Action;

use Crud\Traits\FindMethodTrait;
use Crud\Traits\ViewTrait;
use Crud\Traits\ViewVarTrait;

class ForgotPasswordAction extends BaseAction
{

    use FindMethodTrait;
    use ViewTrait;
    use ViewVarTrait;

    protected $_defaultConfig = [
        'enabled' => true,
        'scope' => 'entity',
        'findConfig' => [],
        'findMethod' => 'first',
        'view' => null,
        'viewVar' => null,
        'serialize' => []
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
            'entity' => $this->_entity($this->_request()->query ?: null)
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
            'findConfig' => $this->_getFindConfig(),
            'findMethod' => $this->config('findMethod')
        ]);

        $this->_trigger('beforeForgotPassword', $subject);

        $query = $this->_table()->find($subject->findMethod, $subject->findConfig);
        if ($query->count()) {
            return $this->_success($subject, $query);
        }

        return $this->_error($subject);
    }

    protected function _getFindConfig()
    {
        $config = (array)$this->config('findConfig') + ['conditions' => []];
        $config['conditions'] = array_merge($config['conditions'], $this->_request()->data);
        return $config;
    }

    /**
     * Post success callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return \Cake\Network\Response
     */
    protected function _success(Subject $subject)
    {
        $subject->set(['success' => true]);

        $this->_trigger('afterForgotPassword', $subject);
        $this->setFlash('success', $subject);

        return $this->_redirect($subject, $this->_controller()->Auth->config('loginAction'));
    }

    /**
     * Post error callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return void
     */
    protected function _error(Subject $subject)
    {
        $subject->set(['success' => false]);

        $this->_trigger('afterForgotPassword', $subject);
        $this->setFlash('error', $subject);
        $this->_trigger('beforeRender', $subject);
    }
}
