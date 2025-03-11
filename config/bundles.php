<?php

use DsElasticSearchBundle\DsElasticSearchBundle;
use DsTrinityDataBundle\DsTrinityDataBundle;
use DynamicSearchBundle\DynamicSearchBundle;
use Pentatrion\ViteBundle\PentatrionViteBundle;
use Pimcore\Bundle\SimpleBackendSearchBundle\PimcoreSimpleBackendSearchBundle;
use Symfony\UX\LiveComponent\LiveComponentBundle;
use Symfony\UX\TwigComponent\TwigComponentBundle;
use Symfony\UX\StimulusBundle\StimulusBundle;

return [
    LiveComponentBundle::class => ['all' => true],
    DsElasticSearchBundle::class => ['all' => true],
    DsTrinityDataBundle::class => ['all' => true],
    DynamicSearchBundle::class => ['all' => true],
    PentatrionViteBundle::class => ['all' => true],
    PimcoreSimpleBackendSearchBundle::class => ['all' => true],
    StimulusBundle::class => ['all' => true],
    TwigComponentBundle::class => ['all' => true],
];
