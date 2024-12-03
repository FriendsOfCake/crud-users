<?php
declare(strict_types=1);

namespace CrudUsers\Traits;

use Cake\Datasource\EntityInterface;

/**
 * @method \Cake\ORM\Table _model()
 */
trait VerifyTrait
{
    /**
     * Get token
     *
     * @param string|null $token Token
     * @return string|null Token if found or null
     */
    protected function _token(?string $token = null): ?string
    {
        if ($token) {
            return $token;
        }

        /** @var string|null $token */
        $token = $this->_request()->getData('token');
        if ($token) {
            return $token;
        }

        /** @var string|null $token */
        $token = $this->_request()->getParam('token');
        if ($token) {
            return $token;
        }

        /** @var string|null */
        return $this->_request()->getQuery('token');
    }

    /**
     * Verify token
     *
     * @param string|null $token Token.
     * @return \Cake\Datasource\EntityInterface
     * @throws \Exception
     */
    protected function _verify(?string $token): EntityInterface
    {
        if (empty($token)) {
            $this->_tokenError();
        }

        $subject = $this->_subject();
        $subject->set([
            'token' => $token,
            'repository' => $this->_model(),
            'query' => $this->_model()->find(
                $this->findMethod(),
                token: $token,
                conditions: [$this->_model()->aliasField($this->getConfig('tokenField')) => $token],
            ),
        ]);

        $this->_trigger('beforeFind', $subject);
        $entity = $subject->query->first();

        if (empty($entity)) {
            $this->_tokenError();
        }

        $subject->set(['entity' => $entity]);
        $this->_trigger('afterFind', $subject);

        if (!isset($subject->verified)) {
            $subject->set(['verified' => false]);
        }
        $this->_trigger('verifyToken', $subject);

        if (!$subject->verified) {
            $this->_tokenError('tokenExpired');
        }

        return $subject->entity;
    }

    /**
     * Throw exception if token not found or expired.
     *
     * @param string $error Error type. Default "tokenNotFound"
     * @return void
     * @throws \Exception
     */
    protected function _tokenError(string $error = 'tokenNotFound'): void
    {
        $subject = $this->_subject(['success' => false]);
        $this->_trigger($error, $subject);

        $message = $this->message($error);
        /** @var class-string<\Exception> $exceptionClass  */
        $exceptionClass = $message['class'];
        throw new $exceptionClass($message['text'], $message['code']);
    }
}
