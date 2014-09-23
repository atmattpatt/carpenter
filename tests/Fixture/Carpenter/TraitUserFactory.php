<?php

namespace Fixture\Carpenter;

use Carpenter\Annotation\Factory;

/** @Factory("\Fixture\Carpenter\User") */
class TraitUserFactory extends BasicUserFactory
{
    public $status = 'new';

    /** @Trait */
    public function deleted($template)
    {
        $template->status = 'deleted';
        $template->password = null;
    }
}
