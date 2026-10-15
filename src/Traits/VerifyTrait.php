<?php
declare(strict_types=1);

namespace CrudUsers\Traits;

use Cake\Datasource\EntityInterface;

/**
 * @method \Cake\ORM\Table model()
 */
trait VerifyTrait
{
    /**
     * Get token
     *
     * @param string|null $token Token
     * @return string|null Token if found or null
     */
    protected function token(?string $token = null): ?string
    {
        if ($token) {
            return $token;
        }

        /** @var string|null $token */
        $token = $this->request()->getData('token');
        if ($token) {
            return $token;
        }

        /** @var string|null $token */
        $token = $this->request()->getParam('token');
        if ($token) {
            return $token;
        }

        /** @var string|null */
        return $this->request()->getQuery('token');
    }

    /**
     * Verify token
     *
     * @param string|null $token Token.
     * @return \Cake\Datasource\EntityInterface
     * @throws \Exception
     */
    protected function verify(?string $token): EntityInterface
    {
        if (empty($token)) {
            $this->tokenError();
        }

        $subject = $this->subject();
        $subject->set([
            'token' => $token,
            'repository' => $this->model(),
            'query' => $this->model()->find(
                $this->findMethod(),
                token: $token,
                conditions: [$this->model()->aliasField($this->getConfig('tokenField')) => $token],
            ),
        ]);

        $this->trigger('beforeFind', $subject);
        $entity = $subject->query->first();

        if (empty($entity)) {
            $this->tokenError();
        }

        $subject->set(['entity' => $entity]);
        $this->trigger('afterFind', $subject);

        if (!isset($subject->verified)) {
            $subject->set(['verified' => false]);
        }
        $this->trigger('verifyToken', $subject);

        if (!$subject->verified) {
            $this->tokenError('tokenExpired');
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
    protected function tokenError(string $error = 'tokenNotFound'): void
    {
        $subject = $this->subject(['success' => false]);
        $this->trigger($error, $subject);

        $message = $this->message($error);
        /** @var class-string<\Exception> $exceptionClass  */
        $exceptionClass = $message['class'];
        throw new $exceptionClass($message['text'], $message['code']);
    }
}
