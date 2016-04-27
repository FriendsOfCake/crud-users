<?php

namespace CrudUsers\Action;

use Crud\Action\BaseAction;
use Crud\Event\Subject;
use Crud\Traits\FindMethodTrait;
use Crud\Traits\RedirectTrait;
use Crud\Traits\SaveMethodTrait;

class ChangePasswordAction extends BaseAction
{

    use FindMethodTrait;
    use RedirectTrait;
    use SaveMethodTrait;

    protected $_defaultConfig = [
        'enabled' => true,
        'scope' => 'entity',
        'findMethod' => 'all',
        'saveMethod' => 'save',
        'relatedModels' => true,
        'saveOptions' => [],
        'messages' => [
            'success' => [
                'text' => 'Account updated successfully'
            ],
            'error' => [
                'text' => 'Could not update the account'
            ]
        ],
        'redirectUrl' => null,
    ];

    /**
     * HTTP GET handler
     *
     * @param string $id Record id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException If record not found
     */
    protected function _get($id = null)
    {
        $subject = $this->_subject();
        $subject->set([
            'id' => $id,
            'entity' => $this->_table()->find($this->findMethod())->first(),
        ]);

        $this->_trigger('beforeRender', $subject);
    }

    /**
     * HTTP POST handler
     *
     * Thin proxy for _put
     *
     * @param mixed $id Record id
     * @return void|\Cake\Network\Response
     */
    protected function _post($id = null)
    {
        return $this->_put($id);
    }

    /**
     * HTTP PUT handler
     *
     * @param mixed $id Record id
     * @return void|\Cake\Network\Response
     */
    protected function _put($id = null)
    {
        $subject = $this->_subject();
        $subject->set([
            'id' => $id,
            'query' => $this->_table()->find($this->findMethod()),
        ]);

        $event = $this->_trigger('beforeVerify', $subject);
        $subject->set(['entity' => $subject->query->first()]);

        if (!$subject->entity || $event->isStopped()) {
            return $this->_error($subject);
        }

        $this->_trigger('afterVerify', $subject);

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
        $subject->set(['success' => true, 'created' => false]);

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
        $subject->set(['success' => false, 'created' => false]);

        $this->setFlash('error', $subject);

        $this->_trigger('beforeRender', $subject);
    }
}
