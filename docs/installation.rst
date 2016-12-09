Installation
============

Installing CRUD Users requires only a few steps

Requirements
------------

* CakePHP 3.x

Getting the Code
----------------

The recommended installation method for this plugin is by using composer.

In your aplication forlder execute:

.. code-block:: bash

  composer require friendsofcake/crud-users

Loading the plugin
------------------

Execute the following lines from your application folder:

.. code-block:: bash

    bin/cake plugin load CrudUsers

Required Database Fields
------------------------

The table holding your user schema and data should contain these fields:

- `token` of a type of your choice, for instance of type `VARCHAR`. The following command will generate a migration for this:

  ```shell
  bin/cake bake migration alter_users token:string:unique
  ```
- `verified` of type `BOOLEAN`, e.g. `TINYINT(1)` for MySQL. The following command will generate a migration for this:

  ```shell
  bin/cake bake migration alter_users verified:boolean
  ```
