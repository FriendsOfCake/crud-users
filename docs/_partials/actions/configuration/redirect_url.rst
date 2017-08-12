redirectUrl
^^^^^^^^^^^

The URL to redirect to on success.

.. code-block:: php

    $this->Crud->mapAction(
        'login',
        [
            'className' => 'CrudUsers.Login',
            'redirectUrl' => '/'
        ]
    );

    // OR

    $this->Crud->action()->config('redirectUrl', '/');
