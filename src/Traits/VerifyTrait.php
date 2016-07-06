<?php
namespace CrudUsers\Traits;

trait VerifyTrait
{
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
}
