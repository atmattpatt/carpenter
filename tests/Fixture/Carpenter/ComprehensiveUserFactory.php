<?php

namespace Fixture\Carpenter;

use Carpenter\Annotation\Factory;
use Carpenter\Annotation\Modifier;

/** @Factory("\Fixture\Carpenter\User") */
class ComprehensiveUserFactory extends ModifierUserFactory
{
    public $status = 'new';

    public $salt = null;

    public function salt()
    {
        return "random salt";
    }
}
