<?php
declare(strict_types=1);

namespace CrudUsers\Action;

use Cake\Http\Response;
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

    protected array $defaultConfig = [
        'enabled' => true,
        'scope' => 'entity',
        'inflection' => 'singular',
        'relatedModels' => true,
        'saveMethod' => 'save',
        'saveOptions' => [],
        'view' => null,
        'viewVar' => null,
        'entityKey' => 'entity',
        'redirectUrl' => ['controller' => 'Users', 'action' => 'login'],
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
    protected function handleGet(): void
    {
        $subject = $this->subject([
            'success' => true,
            'entity' => $this->entity(
                $this->request()->getQueryParams() ?: [],
                $this->saveOptions(),
            ),
        ]);

        $this->trigger('beforeRender', $subject);
    }

    /**
     * HTTP POST handler
     *
     * @return \Cake\Http\Response|null
     */
    protected function handlePost(): ?Response
    {
        $subject = $this->subject([
            'entity' => $this->entity($this->request()->getData(), $this->saveOptions()),
            'saveMethod' => $this->saveMethod(),
            'saveOptions' => $this->saveOptions(),
        ]);

        $this->trigger('beforeRegister', $subject);

        /** @var callable $callback */
        $callback = [$this->model(), $subject->saveMethod];
        if ($callback($subject->entity, $subject->saveOptions)) {
            return $this->success($subject);
        }

        $this->error($subject);

        return null;
    }

    /**
     * Post success callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return \Cake\Http\Response|null
     */
    protected function success(Subject $subject): ?Response
    {
        $subject->set(['success' => true, 'created' => true]);

        $this->trigger('afterRegister', $subject);
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
        $subject->set(['success' => false, 'created' => false]);

        $this->trigger('afterRegister', $subject);
        $this->setFlash('error', $subject);
        $this->trigger('beforeRender', $subject);
    }
}
