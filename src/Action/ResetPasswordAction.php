<?php

namespace CrudUsers\Action;

use Crud\Action\BaseAction;
use Crud\Event\Subject;
use Crud\Traits\FindMethodTrait;
use Crud\Traits\RedirectTrait;
use Crud\Traits\SaveMethodTrait;
use Crud\Traits\ViewTrait;
use Crud\Traits\ViewVarTrait;

class ResetPasswordAction extends BaseAction
{

    use FindMethodTrait;
    use RedirectTrait;
    use SaveMethodTrait;
    use ViewTrait;
    use ViewVarTrait;

    protected $_defaultConfig = [
        'enabled' => true,
        'scope' => 'entity',
        'findMethod' => 'all',
        'saveMethod' => 'save',
        'relatedModels' => true,
        'saveOptions' => [],
        'view' => null,
        'messages' => [
            'success' => [
                'text' => 'Account updated successfully'
            ],
            'error' => [
                'text' => 'Could not update the account'
            ],
            'tokenNotFound' => [
                'code' => 404,
                'class' => 'Cake\Network\Exception\NotFoundException',
                'text' => 'Token not found'
            ],
            'tokenExpired' => [
                'code' => 400,
                'class' => 'Cake\Network\Exception\BadRequestException',
                'text' => 'Token has expired'
            ],
        ],
        'redirectUrl' => null,
    ];

    /**
     * HTTP GET handler
     *
     * @param string $token Token
     * @return void
     */
    protected function _get($token = null)
    {
        $token = $this->_token($token);

        $subject = $this->_subject([
            'success' => true,
            'entity' => $this->_table()->newEntity(),
            'token' => $token
        ]);

        $this->_trigger('beforeRender', $subject);
    }

    /**
     * Get token

     * @param string|null $token Token
     * @return string|null Token if found or null
     */
    protected function _token($token = null)
    {
        if ($token) {
            return $token;
        }

        $token = $this->_request()->data('token');
        if ($token) {
            return $token;
        }

        $token = $this->_request()->param('token');
        if ($token) {
            return $token;
        }

        return $this->_request()->query('token');
    }

    /**
     * Verify token
     *
     * @param string $token Token
     * @return \Cake\Datasource\EntityTrait|null
     */
    protected function _verify($token = null)
    {
        $subject = $this->_subject();
        $subject->set([
            'token' => $token,
            'repository' => $this->_table(),
            'query' => $this->_table()->find(
                $this->findMethod(),
                [
                    'token' => $token,
                    'conditions' => [$this->_table()->aliasField('token') => $token]
                ]
            ),
        ]);

        $this->_trigger('beforeFind', $subject);
        $entity = $subject->query->first();

        $error = 'tokenNotFound';
        if ($entity) {
            $subject->set(['entity' => $entity]);
            $this->_trigger('afterFind', $subject);

            if (!isset($subject->verified)) {
                $subject->set(['verified' => false]);
            }
            $this->_trigger('verifyToken', $subject);
            if ($subject->verified) {
                return $subject->entity;
            }

            $error = 'tokenExpired';
        }

        $subject->set(['success' => false]);
        $this->_trigger($error, $subject);

        $message = $this->message($error, compact('token'));
        $exceptionClass = $message['class'];
        throw new $exceptionClass($message['text'], $message['code']);
    }

    /**
     * HTTP POST handler
     *
     * Thin proxy for _put
     *
     * @param string|null $token Token
     * @return void|\Cake\Network\Response
     */
    protected function _post($token = null)
    {
        return $this->_put($token);
    }

    /**
     * HTTP PUT handler
     *
     * @param string|null $token Token
     * @return void|\Cake\Network\Response
     */
    protected function _put($token = null)
    {
        $entity = $this->_verify($this->_token($token));

        $subject = $this->_subject(compact('entity'));
        if ($this->_save($subject)) {
            return $this->_success($subject);
        }
        return $this->_error($subject);
    }

    /**
     * Save the updated record
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return bool
     */
    protected function _save(Subject $subject)
    {
        $entity = $this->_table()->patchEntity(
            $subject->entity,
            $this->_request()->data,
            $this->saveOptions()
        );
        $subject->set(['entity' => $entity]);

        $this->_trigger('beforeSave', $subject);

        $success = call_user_func(
            [$this->_table(), $this->saveMethod()],
            $entity,
            $this->saveOptions()
        );

        $this->_trigger('afterSave', $subject);

        return $success;
    }

    /**
     * Post success callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return \Cake\Network\Response
     */
    protected function _success(Subject $subject)
    {
        $subject->set(['success' => true]);

        $this->_trigger('afterResetPassword', $subject);
        $this->setFlash('success', $subject);

        if ($this->config('redirectUrl') === null) {
            $redirectUrl = $this->_controller()->Auth->config('loginAction');
        } else {
            $redirectUrl = $this->config('redirectUrl');
        }

        return $this->_redirect($subject, $redirectUrl);
    }

    /**
     * Post error callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return void
     */
    protected function _error(Subject $subject)
    {
        $subject->set(['success' => false]);

        $this->_trigger('afterResetPassword', $subject);
        $this->setFlash('error', $subject);
        $this->_trigger('beforeRender', $subject);
    }
}
