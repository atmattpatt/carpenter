<?php

namespace Fixture\Carpenter;

/** @Factory(\Fixture\Carpenter\User) */
class BasicUserFactory extends BasicUserFactory
{
    public function password($template)
    {
        return sha1($template->username . "password1");
    }
}
