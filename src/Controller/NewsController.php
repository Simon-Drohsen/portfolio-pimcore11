<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Model\DataObject\News;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/news', name: 'app_news_')]
class NewsController extends FrontendController
{
    #[Route('', name: 'list')]
    public function newsAction(Request $request, PaginatorInterface $paginator): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 6;
        $newsQuery = (new News\Listing())->load();

        $pagination = $paginator->paginate(
            $newsQuery,
            $page,
            $limit
        );

        return $this->render('default/list.html.twig', [
            'item_list' => $pagination,
        ]);
    }

    #[Route('/{slug}', name: 'detail')]
    public function newsDetailAction(Request $request, $slug): Response
    {
        $news = News::getBySlug($slug);

        return $this->render('default/detail.html.twig', [
            'item' => $news->getObjects()[0],
        ]);
    }
}
