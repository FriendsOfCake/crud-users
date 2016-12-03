[![Build Status](https://img.shields.io/travis/FriendsOfCake/crud-users/master.svg?style=flat-square)](https://travis-ci.org/FriendsOfCake/crud-users)
[![Total Downloads](https://img.shields.io/packagist/dt/FriendsOfCake/crud-users.svg?style=flat-square)](https://packagist.org/packages/FriendsOfCake/crud-users)
[![Latest Stable Version](https://img.shields.io/packagist/v/FriendsOfCake/crud-users.svg?style=flat-square)](https://packagist.org/packages/FriendsOfCake/crud-users)

# Crud Users

## Installation

For CakePHP 3.x compatible version:

```
composer require friendsofcake/crud-users
```

## Requirements

The table holding your user schema and data should contain these fields:

- `token` of a type of your choice, for instance of type `VARCHAR`. The following command will generate a migration for this:

  ```shell
  bin/cake bake migration alter_users token:string:unique
  ```
- `verified` of type `BOOLEAN`, e.g. `TINYINT(1)` for MySQL. The following command will generate a migration for this:

  ```shell
  bin/cake bake migration alter_users verified:boolean
  ```

## Features

- LoginAction (beforeLogin, afterLogin, beforeRender), use it:

```
$this->Crud->mapAction('login', 'CrudUsers.Login');
```

- LogoutAction (beforeLogout, afterLogout)

```
$this->Crud->mapAction('logout', 'CrudUsers.Logout');
```

- RegisterAction (beforeRegister, afterRegister)

```
$this->Crud->mapAction('register', 'CrudUsers.Register');
```

- ForgotPasswordAction (beforeForgotPassword, afterForgotPassword)

```
$this->Crud->mapAction('forgotPassword', 'CrudUsers.ForgotPassword');
```

- ResetPasswordAction (beforeRender, beforeFind, afterFind, verifyToken, beforeSave, afterSave, afterResetPassword)

```
$this->Crud->mapAction('resetPassword', 'CrudUsers.ResetPassword');
```

- VerifyAction (beforeFind, afterFind, verifyToken, beforeSave, afterSave, afterVerify)

```
$this->Crud->mapAction('verify', 'CrudUsers.Verify');
```

# Bugs

If you happen to stumble upon a bug, please feel free to create a pull request with a fix
(optionally with a test), and a description of the bug and how it was resolved.

You can also create an issue with a description to raise awareness of the bug.

# Support / Questions

You can join us on IRC in the #FriendsOfCake channel for any support or questions.
