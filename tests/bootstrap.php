<?php

require(__DIR__ . '/../vendor/autoload.php');

$namespaces = include(__DIR__ . '/../vendor/composer/autoload_namespaces.php');

Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespaces($namespaces);
