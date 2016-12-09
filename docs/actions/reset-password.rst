ResetPasswordAction
===================

Enable it via:

.. code-block:: php

    $this->Crud->mapAction('resetPassword', 'CrudUsers.ResetPassword');

Triggers:

- beforeRender
- beforeFind
- afterFind
- verifyToken
- beforeSave
- afterSave
- afterResetPassword
