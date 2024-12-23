<?php
declare(strict_types=1);

namespace CrudUsers\Action;

use Cake\Datasource\EntityInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Crud\Action\BaseAction;
use Crud\Error\Exception\ValidationException;
use Crud\Traits\FindMethodTrait;
use Crud\Traits\RedirectTrait;
use Crud\Traits\SaveMethodTrait;
use Crud\Traits\ViewTrait;
use Crud\Traits\ViewVarTrait;
use CrudUsers\Traits\VerifyTrait;

class VerifyAction extends BaseAction
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
        'tokenField' => 'token',
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
                'text' => 'Account verified successfully',
            ],
            'error' => [
                'text' => 'Could not verify the account',
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
    ];

    /**
     * HTTP GET handler
     *
     * @param string|null $token Token
     * @return \Cake\Http\Response|null
     */
    protected function _get(?string $token = null): ?Response
    {
        $token = $this->_token($token);
        $entity = $this->_verify($token);

        if ($this->_save($entity)) {
            return $this->_success();
        }

        $this->_error();

        return null;
    }

    /**
     * Save the updated record
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity
     * @return \Cake\Datasource\EntityInterface|false
     */
    protected function _save(EntityInterface $entity): EntityInterface|false
    {
        $entity = $this->_model()->patchEntity(
            $entity,
            ['verified' => true],
            $this->saveOptions()
        );
        $subject = $this->_subject(['entity' => $entity]);

        $this->_trigger('beforeSave', $subject);

        /** @var callable $callback */
        $callback = [$this->_model(), $this->saveMethod()];

        /** @var \Cake\Datasource\EntityInterface|false $success */
        $success = $callback($entity, $this->saveOptions());

        $this->_trigger('afterSave', $subject);

        return $success;
    }

    /**
     * Post success callback
     *
     * @return \Cake\Http\Response|null
     */
    protected function _success(): ?Response
    {
        $subject = $this->_subject(['success' => true]);

        $this->_trigger('afterVerify', $subject);
        $this->setFlash('success', $subject);

        $redirectUrl = $this->getConfig('redirectUrl');

        return $this->_redirect($subject, $redirectUrl);
    }

    /**
     * Post error callback
     *
     * @return void
     */
    protected function _error(): void
    {
        $subject = $this->_subject(['success' => false]);

        $this->_trigger('afterVerify', $subject);
        $this->setFlash('error', $subject);
        $this->_trigger('beforeRender', $subject);
    }
}
