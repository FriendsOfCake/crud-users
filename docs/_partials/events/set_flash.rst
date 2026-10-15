Crud.setFlash
^^^^^^^^^^^^^

Simple and event driven wrapper for ``FlashComponent::set()``.

The `Crud Subject` contains the following keys:

- **text**    The ``$message`` argument to ``FlashComponent::set()``.
- **element** The ``element`` key in the options passed to ``FlashComponent::set()``.
- **params**  The ``params`` key in the options passed to ``FlashComponent::set()``.
- **key**     The ``key`` key in the options passed to ``FlashComponent::set()``.
- **entity**  (Optional) The ``Entity`` from the previously emitted event.

All keys can be modified as you see fit, at the end of the event cycle the ``text`` key will be
passed as the message and the remaining ones as options directly to ``FlashComponent::set()``.

Defaults are stored in the ``messages`` configuration array for each action.

If you do not want to use this feature, simply stop the event by calling it's ``stopPropagation()`` method.

If you'd like to customise the flash messages that are used, perhaps you're using
`friendsofcake/bootstrap-ui <https://github.com/friendsofcake/bootstrap-ui>`_. It's actually quite simple to do, and can
be done as part of the component configuration or on the fly.

.. code-block:: phpinline

  public function initialize()
  {
        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'edit' => [
                    'className' => 'Crud.Edit',
                    'messages' => [
                        'success' => [
                            'params' => ['class' => 'alert alert-success alert-dismissible']
                        ],
                        'error' => [
                            'params' => ['class' => 'alert alert-danger alert-dismissible']
                        ]
                    ],
                ]
            ]
        ]);
  }

If you'd like to configure it on the fly you can use the eventManager to change the event subject as the event is emitted.

.. code-block:: phpinline

  $this->getEventManager()->on('Crud.setFlash', function (\Cake\Event\EventInterface $event) {
      if ($event->getSubject()->success) {
          $event->getSubject()->params['class'] = 'alert alert-success alert-dismissible';
      }
  });

.. _Crud Subject: https://crud.readthedocs.io/en/latest/crud-subject.html
