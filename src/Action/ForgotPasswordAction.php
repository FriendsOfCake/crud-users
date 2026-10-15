<?php
declare(strict_types=1);

namespace CrudUsers\Action;

use Cake\Http\Response;
use Crud\Action\BaseAction;
use Crud\Event\Subject;
use Crud\Traits\FindMethodTrait;
use Crud\Traits\RedirectTrait;
use Crud\Traits\ViewTrait;
use Crud\Traits\ViewVarTrait;

class ForgotPasswordAction extends BaseAction
{
    use FindMethodTrait;
    use RedirectTrait;
    use ViewTrait;
    use ViewVarTrait;

    protected array $defaultConfig = [
        'enabled' => true,
        'scope' => 'entity',
        'findMethod' => 'all',
        'tokenField' => 'token',
        'messages' => [
            'success' => [
                'text' => 'A recovery email has been sent',
            ],
            'error' => [
                'text' => 'No matching user found',
            ],
        ],
        'serialize' => [],
        'view' => null,
        'viewVar' => null,
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
                $this->request()->getQueryParams(),
                ['validate' => false],
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
            'findMethod' => $this->getFindConfig(),
        ]);

        $this->trigger('beforeForgotPassword', $subject);

        $entity = $this->model()
            ->find($subject->findMethod[0], ...$subject->findMethod[1])
            ->first();

        if (empty($entity)) {
            $this->error($subject);

            return null;
        }

        $subject->set(['entity' => $entity]);

        return $this->success($subject);
    }

    /**
     * Get the query configuration
     *
     * @return array
     */
    protected function getFindConfig(): array
    {
        $finder = $this->extractFinder();
        if (isset($finder[1]['conditions'])) {
            $finder[1]['conditions'] = array_merge($finder[1]['conditions'], $this->request()->getData());
        } else {
            $finder[1]['conditions'] = $this->request()->getData();
        }

        return $finder;
    }

    /**
     * Post success callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return \Cake\Http\Response|null
     */
    protected function success(Subject $subject): ?Response
    {
        $subject->set(['success' => true]);

        $this->trigger('afterForgotPassword', $subject);
        $this->setFlash('success', $subject);

        return $this->redirect($subject, $this->request()->getPath());
    }

    /**
     * Post error callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return void
     */
    protected function error(Subject $subject): void
    {
        $subject->set(['success' => false, 'entity' => null]);

        $this->trigger('afterForgotPassword', $subject);
        $this->setFlash('error', $subject);
        $this->trigger('beforeRender', $subject);
    }
}
