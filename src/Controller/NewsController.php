<?php

namespace App\Controller;

use Pimcore\Model\DataObject\News;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/news', name: 'app_news_')]
class NewsController extends FrontendController
{
    #[Route('', name: 'list')]
    public function newsAction(Request $request): Response
    {
        $blogListing = new News\Listing();
        $newsList = $blogListing->getObjects();
        return $this->render('news/news.html.twig', [
            'news_list' => $newsList,
        ]);
    }

    #[Route('/{slug}', name: 'detail')]
    public function newsDetailAction(Request $request, $slug): Response
    {
        $news = News::getBySlug($slug);

        return $this->render('news/news_detail.html.twig', [
            'news' => $news->getObjects()[0],
        ]);
    }
}
