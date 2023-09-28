<?php
declare(strict_types=1);

namespace CrudUsers\Action;

use Cake\Http\Response;
use Crud\Action\BaseAction;
use Crud\Traits\RedirectTrait;

class LogoutAction extends BaseAction
{
    use RedirectTrait;

    protected array $_defaultConfig = [
        'enabled' => true,
        'messages' => [
            'success' => [
                'text' => 'Successfully logged you out',
            ],
        ],
        'redirectUrl' => null,
    ];

    /**
     * HTTP GET handler
     *
     * @return \Cake\Http\Response|null
     */
    protected function _get(): ?Response
    {
        $subject = $this->_subject();
        $this->_trigger('beforeLogout', $subject);

        /** @psalm-suppress UndefinedMagicPropertyFetch */
        $redirectUrl = $this->_controller()->Authentication->logout();
        $redirectUrl = $this->getConfig('redirectUrl', $redirectUrl);
        if ($redirectUrl === false) {
            $redirectUrl = ['controller' => 'Users', 'action' => 'login'];
        }

        $subject->set([
            'success' => true,
            'redirectUrl' => $redirectUrl,
        ]);

        $this->_trigger('afterLogout', $subject);
        $this->setFlash('success', $subject);

        return $this->_redirect(
            $subject,
            $subject->redirectUrl
        );
    }
}
