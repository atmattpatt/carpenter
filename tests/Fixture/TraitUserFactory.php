<?php

namespace Fixture\Carpenter;

/** @Factory(\Fixture\Carpenter\User) */
class TraitUserFactory extends BasicUserFactory
{
    $status = 'new';

    /** @Trait */
    public function deleted($template)
    {
        $template->status = 'deleted';
        $template->password = null;
    }
}
