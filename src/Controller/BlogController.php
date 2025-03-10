<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Model\DataObject\Blog;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog', name: 'app_blog_')]
class BlogController extends FrontendController
{
    #[Route('', name: 'list')]
    public function blogAction(Request $request, PaginatorInterface $paginator): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 6;
        $blogQuery = (new Blog\Listing())->load();

        $pagination = $paginator->paginate(
            $blogQuery,
            $page,
            $limit
        );

        return $this->render('default/list.html.twig', [
            'item_list' => $pagination,
        ]);
    }

    #[Route('/{slug}', name: 'detail')]
    public function blogDetailAction(Request $request, $slug): Response
    {
        $blog = Blog::getBySlug($slug);

        return $this->render('default/detail.html.twig', [
            'item' => $blog->getObjects()[0],
        ]);
    }
}
