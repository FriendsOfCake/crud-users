[![Build Status](https://img.shields.io/travis/FriendsOfCake/crud-users/master.svg?style=flat-square)](https://travis-ci.org/FriendsOfCake/crud-users)
[![Coverage Status](https://img.shields.io/coveralls/FriendsOfCake/crud-users.svg?style=flat-square)](https://coveralls.io/r/FriendsOfCake/crud-users?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/FriendsOfCake/crud-users.svg?style=flat-square)](https://packagist.org/packages/FriendsOfCake/crud-users)
[![Latest Stable Version](https://img.shields.io/packagist/v/FriendsOfCake/crud-users.svg?style=flat-square)](https://packagist.org/packages/FriendsOfCake/crud-users)
[![Documentation Status](https://readthedocs.org/projects/crud-users/badge/?version=latest&style=flat-square)](https://readthedocs.org/projects/crud-users/?badge=latest)

# Crud Users

**DO NOT USE - VERY EARLY IN DEVELOPMENT**

## Features

- LoginAction (beforeLogin, afterLogin, beforeRender), use it:

```
$this->Crud->mapAction('login', 'CrudUsers.Login');
```

- LogoutAction (beforeLogout, afterLogout)

```
$this->Crud->mapAction('login', 'CrudUsers.Logout');
```

## TODO

- ForgetPasswordAction
- RegisterAction
