<?php
declare(strict_types=1);

namespace CrudUsers\Action;

use Cake\Datasource\EntityInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Crud\Action\BaseAction;
use Crud\Error\Exception\ValidationException;
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

    protected array $_defaultConfig = [
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
                    'class' => ValidationException::class,
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
                'class' => NotFoundException::class,
                'text' => 'Token not found',
            ],
            'tokenExpired' => [
                'code' => 400,
                'class' => BadRequestException::class,
                'text' => 'Token has expired',
            ],
        ],
        'redirectUrl' => ['controller' => 'Users', 'action' => 'login'],
        'tokenField' => 'token',
    ];

    /**
     * HTTP GET handler
     *
     * @param string $token Token
     * @return void
     */
    protected function _get(?string $token = null): void
    {
        $token = $this->_token($token);

        $subject = $this->_subject([
            'success' => true,
            'entity' => $this->_model()->newEmptyEntity(),
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
     * @return \Cake\Http\Response|null
     */
    protected function _post(?string $token = null): ?Response
    {
        return $this->_put($token);
    }

    /**
     * HTTP PUT handler
     *
     * @param string|null $token Token
     * @return \Cake\Http\Response|null
     */
    protected function _put(?string $token = null): ?Response
    {
        $entity = $this->_verify($this->_token($token));

        $subject = $this->_subject(compact('entity'));
        if ($this->_save($subject)) {
            return $this->_success($subject);
        }

        $this->_error($subject);

        return null;
    }

    /**
     * Save the updated record
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return \Cake\Datasource\EntityInterface|false
     */
    protected function _save(Subject $subject): EntityInterface|false
    {
        $entity = $this->_model()->patchEntity(
            $subject->entity,
            $this->_request()->getData(),
            $this->saveOptions()
        );
        $subject->set(['entity' => $entity]);

        $this->_trigger('beforeSave', $subject);

        /** @var callable $callable */
        $callable = [$this->_model(), $this->saveMethod()];

        /** @var \Cake\Datasource\EntityInterface|false $success */
        $success = $callable($entity, $this->saveOptions());

        $this->_trigger('afterSave', $subject);

        return $success;
    }

    /**
     * Post success callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return \Cake\Http\Response
     */
    protected function _success(Subject $subject): ?Response
    {
        $subject->set(['success' => true]);

        $this->_trigger('afterResetPassword', $subject);
        $this->setFlash('success', $subject);

        $redirectUrl = $this->getConfig('redirectUrl');

        return $this->_redirect($subject, $redirectUrl);
    }

    /**
     * Post error callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return void
     */
    protected function _error(Subject $subject): void
    {
        $subject->set(['success' => false]);

        $this->_trigger('afterResetPassword', $subject);
        $this->setFlash('error', $subject);
        $this->_trigger('beforeRender', $subject);
    }
}
