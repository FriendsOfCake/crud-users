LoginAction
===========

Enable it via:

.. code-block:: php

    $this->Crud->mapAction(
        'login',
        'CrudUsers.Login'
    );

Configuration
-------------

.. include:: /_partials/actions/configuration_intro.rst
.. include:: /_partials/actions/configuration/enabled.rst

Action-Specific Events
----------------------

This is a list of events emitted from the ``Login Crud Action``.

Please see the `Events Documentation` for a full list of generic properties
and how to use the event system correctly.

Crud.beforeLogin
^^^^^^^^^^^^^^^^

TODO

Crud.afterLogin
^^^^^^^^^^^^^^^

TODO

Generic Events
--------------

.. include:: /_partials/events/startup.rst
.. include:: /_partials/events/before_filter.rst
.. include:: /_partials/events/set_flash.rst
.. include:: /_partials/events/before_redirect.rst
.. include:: /_partials/events/before_render.rst

.. _Events Documentation: https://crud.readthedocs.io/en/latest/events.html
