<?php

namespace Fixture\Carpenter;

use Carpenter\Annotation\Factory;
use Carpenter\Annotation\Modifier;

/** @Factory("\Fixture\Carpenter\DoctrineUser") */
class DoctrineUserFactory
{
    public $username = 'bob';
    public $password = 'password1';
    public $status = 'new';

    /** @Modifier */
    public function deleted()
    {
        $this->status = 'deleted';
        $this->password = null;
    }
}
