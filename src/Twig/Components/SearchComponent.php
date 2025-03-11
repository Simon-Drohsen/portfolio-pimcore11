<?php

namespace App\Twig\Components;

use App\Controller\SearchController;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('SearchComponent', template: 'components/searchComponent.html.twig')]
class SearchComponent
{
    use DefaultActionTrait;

    public const PER_PAGE = 6;

    #[LiveProp]
    public int $page = 1;

    #[LiveProp(writable: true)]
    public ?string $category = null;

    #[LiveProp(writable: true, url: true)]
    public string $query = '';
    private ?Request $request;

    public function __construct(
        private readonly SearchController $controller,
        private readonly PaginatorInterface $paginator,
        RequestStack $requestStack
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->page = max(1, $this->request->query->getInt('page', 1));
    }

    public function getResults(): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->controller->search($this->query),
            $this->page,
            self::PER_PAGE,
        );
    }

    #[LiveAction]
    public function setCategory(string $category)
    {
        $this->category = $category;
        $this->page = 1;
    }
}
