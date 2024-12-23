Crud.beforeFind
^^^^^^^^^^^^^^^

The event is emitted before calling the find method in the table.

The `Crud Subject` contains the following keys:

- **id** The ID that was originally passed to the action and usually the primary key value of your table.
- **repository** An instance of the ``Repository`` (``Table``) which the query will be executed against.
- **query** A ``Query`` object from the ``Repository`` where ``$PrimaryKey => $IdFromRequest`` is already added to the conditions.

This is the last place you can modify the query before it's executed against the database.

.. note::

  **An example**

  Given the URL: ``/posts/view/10`` the ``repository`` object will be an instance of ``PostsTable`` and the ``query``
  includes a ``WHERE`` condition with ``Posts.id = 10``

After the event has emitted, the database query is executed with ``LIMIT 1``.

If a record is found the ``Crud.afterFind`` event is emitted.

.. warning::

  If no record is found in the database, the ``recordNotFound`` event is emitted instead of ``Crud.afterFind``.

Add Conditions
""""""""""""""

.. code-block:: phpinline

  public function delete($id)
  {
      $this->Crud->on('beforeFind', function(\Cake\Event\EventInterface $event) {
          $event->getSubject()->query->where(['author' => $this->Authentication->getIdentityData('id')]);
      });

      return $this->Crud->execute();
  }

.. _Crud Subject: https://crud.readthedocs.io/en/latest/crud-subject.html
