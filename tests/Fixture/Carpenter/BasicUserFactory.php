<?php

namespace Fixture\Carpenter;

use Carpenter\Annotation\Factory;

/** @Factory("\Fixture\Carpenter\User") */
class BasicUserFactory
{
    public $username = 'Bob';
    public $password = 'password1';
}
