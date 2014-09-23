<?php

namespace Fixture\Carpenter;

use Carpenter\Annotation\Factory;

/** @Factory("\Fixture\Carpenter\User") */
class DynamicUserFactory extends BasicUserFactory
{
    public function password($template)
    {
        return sha1($template->username . "password1");
    }
}
