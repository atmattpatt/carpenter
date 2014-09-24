<?php

namespace Fixture\Carpenter;

use Carpenter\Annotation\Factory;

/** @Factory("\Fixture\Carpenter\User") */
class DynamicUserFactory extends BasicUserFactory
{
    public function password()
    {
        return sha1($this->username . "password1");
    }
}
