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

    protected array $_defaultConfig = [
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
    protected function _get(): void
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
     * @return \Cake\Http\Response|null
     */
    protected function _post(): ?Response
    {
        $subject = $this->_subject([
            'findMethod' => $this->_getFindConfig(),
        ]);

        $this->_trigger('beforeForgotPassword', $subject);

        $entity = $this->_model()
            ->find($subject->findMethod[0], ...$subject->findMethod[1])
            ->first();

        if (empty($entity)) {
            $this->_error($subject);

            return null;
        }

        $subject->set(['entity' => $entity]);

        return $this->_success($subject);
    }

    /**
     * Get the query configuration
     *
     * @return array
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
     * @return \Cake\Http\Response|null
     */
    protected function _success(Subject $subject): ?Response
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
     * @return void
     */
    protected function _error(Subject $subject): void
    {
        $subject->set(['success' => false, 'entity' => null]);

        $this->_trigger('afterForgotPassword', $subject);
        $this->setFlash('error', $subject);
        $this->_trigger('beforeRender', $subject);
    }
}
