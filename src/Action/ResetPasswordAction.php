<?php
declare(strict_types=1);

namespace CrudUsers\Action;

use Crud\Action\BaseAction;
use Crud\Event\Subject;
use Crud\Traits\FindMethodTrait;
use Crud\Traits\RedirectTrait;
use Crud\Traits\SaveMethodTrait;
use Crud\Traits\ViewTrait;
use Crud\Traits\ViewVarTrait;
use CrudUsers\Traits\VerifyTrait;

class ResetPasswordAction extends BaseAction
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
        'saveMethod' => 'save',
        'relatedModels' => true,
        'saveOptions' => [],
        'view' => null,
        'api' => [
            'methods' => ['put', 'post'],
            'success' => [
                'code' => 200,
            ],
            'error' => [
                'exception' => [
                    'type' => 'validate',
                    'class' => '\Crud\Error\Exception\ValidationException',
                ],
            ],
        ],
        'messages' => [
            'success' => [
                'text' => 'Account updated successfully',
            ],
            'error' => [
                'text' => 'Could not update the account',
            ],
            'tokenNotFound' => [
                'code' => 404,
                'class' => 'Cake\Http\Exception\NotFoundException',
                'text' => 'Token not found',
            ],
            'tokenExpired' => [
                'code' => 400,
                'class' => 'Cake\Http\Exception\BadRequestException',
                'text' => 'Token has expired',
            ],
        ],
        'redirectUrl' => null,
        'tokenField' => 'token',
    ];

    /**
     * HTTP GET handler
     *
     * @param string $token Token
     * @return void
     */
    protected function _get(?string $token = null)
    {
        $token = $this->_token($token);

        $subject = $this->_subject([
            'success' => true,
            'entity' => $this->_table()->newEmptyEntity(),
            'token' => $token,
        ]);

        $this->_trigger('beforeRender', $subject);
    }

    /**
     * HTTP POST handler
     *
     * Thin proxy for _put
     *
     * @param string|null $token Token
     * @return void|\Cake\Http\Response
     */
    protected function _post($token = null)
    {
        return $this->_put($token);
    }

    /**
     * HTTP PUT handler
     *
     * @param string|null $token Token
     * @return void|\Cake\Http\Response
     */
    protected function _put(?string $token = null)
    {
        $entity = $this->_verify($this->_token($token));

        $subject = $this->_subject(compact('entity'));
        if ($this->_save($subject)) {
            return $this->_success($subject);
        }

        return $this->_error($subject);
    }

    /**
     * Save the updated record
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return bool
     */
    protected function _save(Subject $subject)
    {
        $entity = $this->_table()->patchEntity(
            $subject->entity,
            $this->_request()->getData(),
            $this->saveOptions()
        );
        $subject->set(['entity' => $entity]);

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
     * @param \Crud\Event\Subject $subject Event subject
     * @return \Cake\Http\Response
     */
    protected function _success(Subject $subject)
    {
        $subject->set(['success' => true]);

        $this->_trigger('afterResetPassword', $subject);
        $this->setFlash('success', $subject);

        if ($this->getConfig('redirectUrl') === null) {
            $redirectUrl = $this->_controller()->Auth->getConfig('loginAction');
        } else {
            $redirectUrl = $this->getConfig('redirectUrl');
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
        $subject->set(['success' => false]);

        $this->_trigger('afterResetPassword', $subject);
        $this->setFlash('error', $subject);
        $this->_trigger('beforeRender', $subject);
    }
}
