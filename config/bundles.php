<?php

use DsElasticSearchBundle\DsElasticSearchBundle;
use DsTrinityDataBundle\DsTrinityDataBundle;
use DynamicSearchBundle\DynamicSearchBundle;
use Pentatrion\ViteBundle\PentatrionViteBundle;
use Pimcore\Bundle\SimpleBackendSearchBundle\PimcoreSimpleBackendSearchBundle;
use Symfony\UX\TwigComponent\TwigComponentBundle;

return [
    DsElasticSearchBundle::class => ['all' => true],
    DsTrinityDataBundle::class => ['all' => true],
    DynamicSearchBundle::class => ['all' => true],
    PentatrionViteBundle::class => ['all' => true],
    PimcoreSimpleBackendSearchBundle::class => ['all' => true],
    TwigComponentBundle::class => ['all' => true],
];
