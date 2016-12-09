VerifyAction
============

Enable it via:

.. code-block:: php

    $this->Crud->mapAction('verify', 'CrudUsers.Verify');

Triggers:

- beforeFind
- afterFind
- verifyToken
- beforeSave
- afterSave
- afterVerify
