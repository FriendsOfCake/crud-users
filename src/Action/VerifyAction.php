<?php

namespace CrudUsers\Action;

use Cake\Datasource\EntityInterface;
use CrudUsers\Traits\VerifyTrait;
use Crud\Action\BaseAction;
use Crud\Event\Subject;
use Crud\Traits\FindMethodTrait;
use Crud\Traits\RedirectTrait;
use Crud\Traits\SaveMethodTrait;
use Crud\Traits\ViewTrait;
use Crud\Traits\ViewVarTrait;

class VerifyAction extends BaseAction
{

    use FindMethodTrait;
    use RedirectTrait;
    use SaveMethodTrait;
    use VerifyTrait;
    use ViewTrait;
    use ViewVarTrait;

    protected $_defaultConfig = [
        'enabled' => true,
        'scope' => 'entity',
        'findMethod' => 'all',
        'tokenField' => 'token',
        'saveMethod' => 'save',
        'relatedModels' => true,
        'saveOptions' => [],
        'view' => null,
        'api' => [
            'methods' => ['put', 'post'],
            'success' => [
                'code' => 200
            ],
            'error' => [
                'exception' => [
                    'type' => 'validate',
                    'class' => '\Crud\Error\Exception\ValidationException'
                ]
            ]
        ],
        'messages' => [
            'success' => [
                'text' => 'Account verified successfully'
            ],
            'error' => [
                'text' => 'Could not verify the account'
            ],
            'tokenNotFound' => [
                'code' => 404,
                'class' => 'Cake\Network\Exception\NotFoundException',
                'text' => 'Token not found'
            ],
            'tokenExpired' => [
                'code' => 400,
                'class' => 'Cake\Network\Exception\BadRequestException',
                'text' => 'Token has expired'
            ],
        ],
        'redirectUrl' => null,
    ];

    /**
     * HTTP GET handler
     *
     * @param string $token Token
     * @return \Cake\Network\Response
     */
    protected function _get($token = null)
    {
        $token = $this->_token($token);
        $entity = $this->_verify($token);

        if ($this->_save($entity)) {
            return $this->_success();
        }

        return $this->_error();
    }

    /**
     * Save the updated record
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity
     * @return bool
     */
    protected function _save(EntityInterface $entity)
    {
        $entity = $this->_table()->patchEntity(
            $entity,
            ['verified' => true],
            $this->saveOptions()
        );
        $subject = $this->_subject(['entity' => $entity]);

        $this->_trigger('beforeSave', $subject);

        $success = call_user_func(
            [$this->_table(), $this->saveMethod()],
            $entity,
            $this->saveOptions()
        );

        $this->_trigger('afterSave', $subject);

        return $success;
    }

    /**
     * Post success callback
     *
     * @return \Cake\Network\Response
     */
    protected function _success()
    {
        $subject = $this->_subject(['success' => true]);

        $this->_trigger('afterVerify', $subject);
        $this->setFlash('success', $subject);

        $redirectUrl = $this->config('redirectUrl');

        if (!$redirectUrl && $this->_controller()->components()->has('Auth')) {
            $redirectUrl = $this->_controller()->Auth->config('loginAction');
        }

        return $this->_redirect($subject, $redirectUrl);
    }

    /**
     * Post error callback
     *
     * @return void|\Cake\Network\Response
     */
    protected function _error()
    {
        $subject = $this->_subject(['success' => false]);

        $this->_trigger('afterVerify', $subject);
        $this->setFlash('error', $subject);
        $this->_trigger('beforeRender', $subject);
    }
}
