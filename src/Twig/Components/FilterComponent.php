<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('FilterComponent', template: 'components/filterComponent.html.twig')]
class FilterComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public int $page = 1;

    #[LiveProp(writable: true)]
    public ?string $category = null;

    public function __construct()
    {
    }

    #[LiveAction]
    public function setCategory(string $category)
    {
        $this->category = $category;
        $this->page = 1;
    }
}
