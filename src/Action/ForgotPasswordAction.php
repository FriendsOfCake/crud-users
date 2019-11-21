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

    protected $_defaultConfig = [
        'enabled' => true,
        'scope' => 'entity',
        'findMethod' => 'all',
        'tokenField' => 'token',
        'messages' => [
            'success' => [
                'text' => 'A recovery email has been sent successfully',
            ],
            'error' => [
                'text' => 'No search results found',
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
    protected function _get()
    {
        $subject = $this->_subject([
            'success' => true,
            'entity' => $this->_entity(
                $this->_request()->getQueryParams(),
                ['validate' => false]
            ),
        ]);

        $this->_trigger('beforeRender', $subject);
    }

    /**
     * HTTP POST handler
     *
     * @return void|\Cake\Http\Response
     */
    protected function _post()
    {
        $subject = $this->_subject([
            'findMethod' => $this->_getFindConfig(),
        ]);

        $this->_trigger('beforeForgotPassword', $subject);

        $entity = $this->_table()
            ->find($subject->findMethod[0], $subject->findMethod[1])
            ->first();

        if (empty($entity)) {
            return $this->_error($subject);
        }

        $subject->set(['entity' => $entity]);

        return $this->_success($subject);
    }

    /**
     * Get the query configuration
     *
     * @return array
     * @psalm-return array{string, array}
     */
    protected function _getFindConfig(): array
    {
        $finder = $this->_extractFinder();
        if (isset($finder[1]['conditions'])) {
            $finder[1]['conditions'] = array_merge($finder[1]['conditions'], $this->_request()->getData());
        } else {
            $finder[1]['conditions'] = $this->_request()->getData();
        }

        return $finder;
    }

    /**
     * Post success callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return \Cake\Http\Response
     */
    protected function _success(Subject $subject): Response
    {
        $subject->set(['success' => true]);

        $this->_trigger('afterForgotPassword', $subject);
        $this->setFlash('success', $subject);

        return $this->_redirect($subject, $this->_request()->getPath());
    }

    /**
     * Post error callback
     *
     * @param \Crud\Event\Subject $subject Event subject
     * @return void|\Cake\Http\Response
     */
    protected function _error(Subject $subject)
    {
        $subject->set(['success' => false, 'entity' => null]);

        $this->_trigger('afterForgotPassword', $subject);
        $this->setFlash('error', $subject);
        $this->_trigger('beforeRender', $subject);
    }
}
