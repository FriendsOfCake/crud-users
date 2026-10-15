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

    protected array $defaultConfig = [
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
    protected function handleGet(?string $token = null): void
    {
        $token = $this->token($token);

        $subject = $this->subject([
            'success' => true,
            'entity' => $this->model()->newEmptyEntity(),
            'token' => $token,
        ]);

        $this->trigger('beforeRender', $subject);
    }

    /**
     * HTTP POST handler
     *
     * Thin proxy for handlePut()
     *
     * @param string|null $token Token
     * @return \Cake\Http\Response|null
     */
    protected function handlePost(?string $token = null): ?Response
    {
        return $this->handlePut($token);
    }

    /**
     * HTTP PUT handler
     *
     * @param string|null $token Token
     * @return \Cake\Http\Response|null
     */
    protected function handlePut(?string $token = null): ?Response
    {
        $entity = $this->verify($this->token($token));

        $subject = $this->subject(compact('entity'));
        if ($this->save($subject)) {
            return $this->success($subject);
        }

        $this->error($subject);

        return null;
    }

    /**
     * Save the updated record
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return \Cake\Datasource\EntityInterface|false
     */
    protected function save(Subject $subject): EntityInterface|false
    {
        $entity = $this->model()->patchEntity(
            $subject->entity,
            $this->request()->getData(),
            $this->saveOptions(),
        );
        $subject->set(['entity' => $entity]);

        $this->trigger('beforeSave', $subject);

        /** @var callable $callable */
        $callable = [$this->model(), $this->saveMethod()];

        /** @var \Cake\Datasource\EntityInterface|false $success */
        $success = $callable($entity, $this->saveOptions());

        $this->trigger('afterSave', $subject);

        return $success;
    }

    /**
     * Post success callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return \Cake\Http\Response
     */
    protected function success(Subject $subject): ?Response
    {
        $subject->set(['success' => true]);

        $this->trigger('afterResetPassword', $subject);
        $this->setFlash('success', $subject);

        $redirectUrl = $this->getConfig('redirectUrl');

        return $this->redirect($subject, $redirectUrl);
    }

    /**
     * Post error callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return void
     */
    protected function error(Subject $subject): void
    {
        $subject->set(['success' => false]);

        $this->trigger('afterResetPassword', $subject);
        $this->setFlash('error', $subject);
        $this->trigger('beforeRender', $subject);
    }
}
