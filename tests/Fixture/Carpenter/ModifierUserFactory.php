<?php

namespace Fixture\Carpenter;

use Carpenter\Annotation\Factory;
use Carpenter\Annotation\Modifier;

/** @Factory("\Fixture\Carpenter\User") */
class ModifierUserFactory extends BasicUserFactory
{
    public $status = 'new';

    /** @Modifier */
    public function deleted()
    {
        $this->status = 'deleted';
        $this->password = null;
    }
}
