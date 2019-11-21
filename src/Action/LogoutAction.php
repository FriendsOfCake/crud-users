<?php
declare(strict_types=1);

namespace CrudUsers\Action;

use Crud\Action\BaseAction;
use Crud\Traits\RedirectTrait;

class LogoutAction extends BaseAction
{
    use RedirectTrait;

    protected $_defaultConfig = [
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
     * @return void|\Cake\Http\Response
     */
    protected function _get()
    {
        $subject = $this->_subject();
        $this->_trigger('beforeLogout', $subject);

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
