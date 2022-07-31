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

    protected $_defaultConfig = [
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
     * @return \Cake\Http\Response|null|void
     */
    protected function _get()
    {
        $result = $this->_controller()->Authentication->getResult();
        $subject = $this->_subject([
            'success' => true,
            'result' => $result,
        ]);

        if ($result && $result->isValid()) {
            return $this->_success($subject);
        }

        $this->_trigger('beforeRender', $subject);
    }

    /**
     * HTTP POST handler
     *
     * @return \Cake\Http\Response|null|void
     */
    protected function _post()
    {
        $result = $this->_controller()->Authentication->getResult();
        $subject = $this->_subject([
            'result' => $result,
        ]);

        if ($result && $result->isValid()) {
            return $this->_success($subject);
        }

        $this->_error($subject);
    }

    /**
     * Post success callback
     *
     * @param \Crud\Event\Subject $subject Event subject.
     * @return \Cake\Http\Response|null
     */
    protected function _success(Subject $subject): ?Response
    {
        $subject->set([
            'success' => true,
            'identity' => $this->_controller()->Authentication->getIdentity(),
        ]);

        if ($this->_request()->is('post')) {
            $this->setFlash('success', $subject);
        }

        /** @psalm-suppress UndefinedInterfaceMethod */
        $redirectUrl = $this->_controller()->Authentication->getLoginRedirect()
                ?? $this->getConfig('redirectUrl');

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

        $this->setFlash('error', $subject);
        $this->_trigger('beforeRender', $subject);
    }
}
