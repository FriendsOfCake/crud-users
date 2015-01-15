<?php

namespace CrudUsers\Action;

use Crud\Action\BaseAction;
use Crud\Event\Subject;
use Crud\Traits\RedirectTrait;

class LogoutAction extends BaseAction
{

    use RedirectTrait;

    protected $_defaultConfig = [
        'enabled' => true,
        'messages' => [
            'success' => [
                'text' => 'Successfully logged you out'
            ],
        ]
    ];

    /**
     * HTTP GET handler
     *
     * @return void|\Cake\Network\Response
     */
    protected function _get()
    {
        $subject = $this->_subject([
            'logoutMethod' => 'logout',
        ]);

        $this->_trigger('beforeLogout', $subject);

        $logoutCallback = [$this->_controller()->Auth, $subject->logoutMethod];
        $redirectUrl = $logoutCallback();
        return $this->_success($subject, $redirectUrl);
    }

    /**
     * Post success callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @param string $redirectUrl Logout redirect URL.
     * @return \Cake\Network\Response
     */
    protected function _success(Subject $subject, $redirectUrl)
    {
        $subject->set(['success' => true, 'redirectUrl' => $redirectUrl]);

        $this->_trigger('afterLogout', $subject);
        $this->setFlash('success', $subject);

        return $this->_redirect(
            $subject,
            $subject->redirectUrl
        );
    }
}
