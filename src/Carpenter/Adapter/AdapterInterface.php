<?php

namespace Carpenter\Adapter;

interface AdapterInterface
{
    public function build($targetClass, $resolved);

    public function persist($built);
}
