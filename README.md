Carpenter
=========

A PHP library for setting up database fixtures, heavily inspired by [FactoryGirl](https://github.com/thoughtbot/factory_girl).

[![Build Status](https://travis-ci.org/matthewpatterson/carpenter.svg?branch=master)](https://travis-ci.org/matthewpatterson/carpenter)

Installation
------------

Add the following to a `composer.json` file:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/matthewpatterson/carpenter.git"
        }
    ],
    "require": {
        "matthewpatterson/carpenter": "*"
    }
}
```

Then run `composer install`.

Eventually, this will be hosted on Packagist.

Usage
-----

### Defining Factories

Factories are free-form classes, allowing for a lot of flexibility in their definition.  All you need to make a class a factory is the `@Factory` annotation.  For example, consider this factory that won't really do much of anything:

```php
<?php
use Carpenter\Annotation\Factory;

/** @Factory("stdclass") */
class BoringFactory
{
}
```

Factories can exist anywhere in your code base.  However, you will need to call `Carpenter\Factory::discoverFactories()` prior to using them, for example in a testing bootstrap script.

### Using Factories

There are two ways to use a factory: `build()` and `create()`.  Both will give you an instance of the fixture you're looking for; the difference is that `create()` will also persist the fixture to a data store.

When invoking `build()` or `create()`, you must supply, at a minimum, the name of the factory to use.  The name of the factory is derived from factory's unqualified classname.  For example, `\Project\Fixture\UserFactory` becomes `User`.

```php
<?php
$user = Carpenter\Factory::build('User');
$persistedUser = Carpenter\Factory::create('User');
```

### A Basic Factory

The most basic thing you can do with a factory is to define properties with static values.  Simple define a public instance property, and every fixture will have the value you assign to it.

```php
<?php
use Carpenter\Annotation\Factory;

/** @Factory("\Project\Model\User") */
class UserFactory
{
    public $firstName = "John";
    public $lastName = "Doe";
    public $email = "john.doe@example.com";
    public $status = "new";
}

$user = Carpenter\Factory::build('User');
$user->firstName == "John"; // true
$user->lastName == "Doe"; // true
$user->email == "john.doe@example.com"; // true
$user->status == "new"; // true
```

### Overriding Properties

A `UserFactory` is all well and good, but what if you want our user to have a slightly different status?  You can provide an array of overrides when building and creating the fixture.

```php
<?php
$user = Carpenter\Factory::build("User", ["status" => "verified"]);
$user->firstName == "John"; // etc.
$user->status == "verified"; // true
```

### Dynamic Properties

Property values can also be generated dynamically, making it easy to generate random data with a tool such as [Faker](https://github.com/fzaninotto/Faker).  All you need is a public instance property and a public instance method of the same name.  Of course, you can still supply override values if needed.

```php
<?php
use Carpenter\Annotation\Factory;

/** @Factory("\Project\Model\User") */
class UserFactory
{
    public $firstName;

    public function firstName()
    {
        $faker = Faker\Factory::create();
        return $faker->firstName;
    }
}

$user = Carpenter\Factory::build('User');
$user->firstName == "Quincy";

$user = Carpenter\Factory::build('User', ["firstName" => "George"]);
$user->firstName == "George";
```

### Modifiers

Modifiers (similar to FactoryGirl traits) define a group of properties and are especially useful for complex domain models.  To create a modifier, simply add the `@Modifier` annotation to a public instance method.  Modifiers act by mutating the instance of the class.  When calling `build()` or `create()`, you can specify modifiers which will be applied in the order given.

```php
<?php
use Carpenter\Annotation\Factory;
use Carpenter\Annotation\Modifier;

/** @Factory("\Project\Model\User") */
class UserFactory
{
    public $admin = false;
    public $roles = [];

    /** @Modifier */
    public function admin()
    {
        $this->admin = true;
        $this->roles = Roles::getAll();
    }

    /** @Modifier */
    public function moderator()
    {
        $this->roles = ['delete_posts', 'edit_posts', 'ban_users'];
    }
}

$adminUser = Carpenter\Factory::build('User', 'admin');
$moderatorUser = Carpenter\Factory::build('User', 'admin', 'moderator'); // Will have $admin == true but the roles of a moderator
$otherModerator = Carpenter\Factory::build('User', 'admin', ["roles" => ["ban_users"]]); // Will have $admin == true but only the ban_users role
```

### Adapters

Adapters build the correct fixture and persist it to a data store.  Currently, there are two adapters available:

* ArrayAdapter
* DoctrineAdapter

### Configuration

The following values should be set prior to using Carpenter.  You can do this, for example, in a testing bootstrap script.

* `Carpenter\Configuration::$adapter` - The adapter to use for building fixtures
* `Captenter\Configuration::$factoryPaths` - An array of paths to search for factories

Contributions
-------------

This project is in its very early stages.  As such, pull requests and isues are always welcome via Github.
