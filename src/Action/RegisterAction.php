<?php
declare(strict_types=1);

namespace CrudUsers\Action;

use Crud\Action\BaseAction;
use Crud\Error\Exception\ValidationException;
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
        'redirectUrl' => null,
        'api' => [
            'methods' => ['put', 'post'],
            'success' => [
                'code' => 201,
                'data' => [
                    'entity' => ['id'],
                ],
            ],
            'error' => [
                'exception' => [
                    'type' => 'validate',
                    'class' => ValidationException::class,
                ],
            ],
        ],
        'messages' => [
            'success' => [
                'text' => 'Account successfully created',
            ],
            'error' => [
                'text' => 'Please fix the errors and try again',
            ],
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
            'entity' => $this->_entity(
                $this->_request()->getQueryParams() ?: [],
                $this->saveOptions()
            ),
        ]);

        $this->_trigger('beforeRender', $subject);
    }

    /**
     * HTTP POST handler
     *
     * @return void|\Cake\Http\Response
     */
    protected function _post()
    {
        $subject = $this->_subject([
            'entity' => $this->_entity($this->_request()->getData(), $this->saveOptions()),
            'saveMethod' => $this->saveMethod(),
            'saveOptions' => $this->saveOptions(),
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
     * @return \Cake\Http\Response
     */
    protected function _success(Subject $subject)
    {
        $subject->set(['success' => true, 'created' => true]);

        $this->_trigger('afterRegister', $subject);
        $this->setFlash('success', $subject);

        $redirectUrl = $this->getConfig('redirectUrl');
        if ($redirectUrl === null && $this->_controller()->components()->has('Auth')) {
            $redirectUrl = $this->_controller()->Auth->getConfig('loginAction');
        }

        return $this->_redirect($subject, $redirectUrl);
    }

    /**
     * Post error callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return void|\Cake\Http\Response
     */
    protected function _error(Subject $subject)
    {
        $subject->set(['success' => false, 'created' => false]);

        $this->_trigger('afterRegister', $subject);
        $this->setFlash('error', $subject);
        $this->_trigger('beforeRender', $subject);
    }
}
