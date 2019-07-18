<?php
namespace CrudUsers\Traits;

trait VerifyTrait
{
    /**
     * Get token
     *
     * @param string|null $token Token
     * @return string|null Token if found or null
     */
    protected function _token($token = null)
    {
        if ($token) {
            return $token;
        }

        $token = $this->_request()->getData('token');
        if ($token) {
            return $token;
        }

        $token = $this->_request()->getParam('token');
        if ($token) {
            return $token;
        }

        return $this->_request()->getQuery('token');
    }

    /**
     * Verify token
     *
     * @param string $token Token.
     * @return \Cake\Datasource\EntityTrait|null
     */
    protected function _verify($token)
    {
        if (empty($token)) {
            $this->_tokenError();
        }

        $subject = $this->_subject();
        $subject->set([
            'token' => $token,
            'repository' => $this->_table(),
            'query' => $this->_table()->find(
                $this->findMethod(),
                [
                    'token' => $token,
                    'conditions' => [$this->_table()->aliasField($this->getConfig('tokenField')) => $token]
                ]
            ),
        ]);

        $this->_trigger('beforeFind', $subject);
        $entity = $subject->query->first();

        if (empty($token)) {
            $this->_tokenError();
        }

        $subject->set(['entity' => $entity]);
        $this->_trigger('afterFind', $subject);

        if (!isset($subject->verified)) {
            $subject->set(['verified' => false]);
        }
        $this->_trigger('verifyToken', $subject);

        if ($subject->verified) {
            return $subject->entity;
        }

        $this->_tokenError('tokenExpired');
    }

    /**
     * Throw exception if token not found or expired.
     *
     * @param string $error Error type. Default "tokenNotFound"
     * @return void
     * @throws \Exception
     */
    protected function _tokenError($error = 'tokenNotFound')
    {
        $subject = $this->_subject(['success' => false]);
        $this->_trigger($error, $subject);

        $message = $this->message($error, compact('token'));
        $exceptionClass = $message['class'];
        throw new $exceptionClass($message['text'], $message['code']);
    }
}
