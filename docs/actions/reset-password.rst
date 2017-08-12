ResetPasswordAction
===================

Enable it via:

.. code-block:: php

    $this->Crud->mapAction(
        'resetPassword',
        'CrudUsers.ResetPassword'
    );

Configuration
-------------

.. include:: /_partials/actions/configuration_intro.rst
.. include:: /_partials/actions/configuration/enabled.rst
.. include:: /_partials/actions/configuration/find_method.rst
.. include:: /_partials/actions/configuration/redirect_url.rst
.. include:: /_partials/actions/configuration/save_options.rst
.. include:: /_partials/actions/configuration/view.rst
.. include:: /_partials/actions/configuration/view_var.rst

Action-Specific Events
----------------------

This is a list of events emitted from the ``ResetPassword Crud Action``.

Please see the `Events Documentation` for a full list of generic properties
and how to use the event system correctly.

Crud.verifyToken
^^^^^^^^^^^^^^^^

TODO

Crud.afterResetPassword
^^^^^^^^^^^^^^^^^^^^^^^

TODO

Generic Events
--------------

.. include:: /_partials/events/startup.rst
.. include:: /_partials/events/before_filter.rst
.. include:: /_partials/events/before_find.rst
.. include:: /_partials/events/after_find.rst
.. include:: /_partials/events/record_not_found.rst
.. include:: /_partials/events/before_save.rst
.. include:: /_partials/events/after_save.rst
.. include:: /_partials/events/set_flash.rst
.. include:: /_partials/events/before_redirect.rst
.. include:: /_partials/events/before_render.rst

.. _Events Documentation: https://crud.readthedocs.io/en/latest/events.html
