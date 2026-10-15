<?php
declare(strict_types=1);

namespace CrudUsers\Action;

use Cake\Http\Response;
use Crud\Action\BaseAction;
use Crud\Event\Subject;
use Crud\Traits\RedirectTrait;

class LoginAction extends BaseAction
{
    use RedirectTrait;

    protected array $defaultConfig = [
        'enabled' => true,
        'messages' => [
            'success' => [
                'text' => 'Successfully logged you in',
            ],
            'error' => [
                'text' => 'Invalid credentials, please try again',
            ],
        ],
        'redirectUrl' => '/',
    ];

    /**
     * HTTP GET handler
     *
     * @return \Cake\Http\Response|null
     */
    protected function handleGet(): ?Response
    {
        /** @psalm-suppress UndefinedMagicPropertyFetch,UndefinedMagicMethod */
        $result = $this->controller()->Authentication->getResult();
        $subject = $this->subject([
            'success' => true,
            'result' => $result,
        ]);

        if ($result && $result->isValid()) {
            return $this->success($subject);
        }

        $this->trigger('beforeRender', $subject);

        return null;
    }

    /**
     * HTTP POST handler
     *
     * @return \Cake\Http\Response|null
     */
    protected function handlePost(): ?Response
    {
        /** @psalm-suppress UndefinedMagicPropertyFetch */
        $result = $this->controller()->Authentication->getResult();
        $subject = $this->subject([
            'result' => $result,
        ]);

        if ($result && $result->isValid()) {
            return $this->success($subject);
        }

        $this->error($subject);

        return null;
    }

    /**
     * Post success callback
     *
     * @param \Crud\Event\Subject $subject Event subject.
     * @return \Cake\Http\Response|null
     */
    protected function success(Subject $subject): ?Response
    {
        /** @psalm-suppress UndefinedMagicPropertyFetch */
        $subject->set([
            'success' => true,
            'identity' => $this->controller()->Authentication->getIdentity(),
        ]);

        if ($this->request()->is('post')) {
            $this->setFlash('success', $subject);
        }

        /** @psalm-suppress UndefinedMagicMethod,UndefinedMethod */
        $redirectUrl = $this->controller()->Authentication->getLoginRedirect()
                ?? $this->getConfig('redirectUrl');

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

        $this->setFlash('error', $subject);
        $this->trigger('beforeRender', $subject);
    }
}
