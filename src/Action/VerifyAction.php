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

    protected array $defaultConfig = [
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
    protected function handleGet(?string $token = null): ?Response
    {
        $token = $this->token($token);
        $entity = $this->verify($token);

        if ($this->save($entity)) {
            return $this->success();
        }

        $this->error();

        return null;
    }

    /**
     * Save the updated record
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity
     * @return \Cake\Datasource\EntityInterface|false
     */
    protected function save(EntityInterface $entity): EntityInterface|false
    {
        $entity = $this->model()->patchEntity(
            $entity,
            ['verified' => true],
            $this->saveOptions(),
        );
        $subject = $this->subject(['entity' => $entity]);

        $this->trigger('beforeSave', $subject);

        /** @var callable $callback */
        $callback = [$this->model(), $this->saveMethod()];

        /** @var \Cake\Datasource\EntityInterface|false $success */
        $success = $callback($entity, $this->saveOptions());

        $this->trigger('afterSave', $subject);

        return $success;
    }

    /**
     * Post success callback
     *
     * @return \Cake\Http\Response|null
     */
    protected function success(): ?Response
    {
        $subject = $this->subject(['success' => true]);

        $this->trigger('afterVerify', $subject);
        $this->setFlash('success', $subject);

        $redirectUrl = $this->getConfig('redirectUrl');

        return $this->redirect($subject, $redirectUrl);
    }

    /**
     * Post error callback
     *
     * @return void
     */
    protected function error(): void
    {
        $subject = $this->subject(['success' => false]);

        $this->trigger('afterVerify', $subject);
        $this->setFlash('error', $subject);
        $this->trigger('beforeRender', $subject);
    }
}
