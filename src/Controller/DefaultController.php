<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Bundle\AdminBundle\Controller\Admin\LoginController;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\Blog;
use Pimcore\Model\DataObject\News;
use Pimcore\Model\DataObject\Project;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends FrontendController
{
    public function defaultAction(): Response
    {
        return $this->render('default/default.html.twig');
    }

    public function listAllAction(Request $request, PaginatorInterface $paginator): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 6;
        $offset = ($page - 1) * $limit;

        $category = $request->query->get('category');
        $items = [];

        if (!$category || $category === 'blog') {
            $blogQuery = (new Blog\Listing())->load();
            $items = array_merge($items, $blogQuery);
        }

        if (!$category || $category === 'news') {
            $newsQuery = (new News\Listing())->load();
            $items = array_merge($items, $newsQuery);
        }

        if (!$category || $category === 'project') {
            $projectQuery = (new Project\Listing())->load();
            $items = array_merge($items, $projectQuery);
        }

        $pagination = $paginator->paginate(
            $items,
            $page,
            $limit
        );

        return $this->render('default/list.html.twig', [
            'category' => $category,
            'item_list' => $pagination,
        ]);
    }

    /**
     * Forwards the request to admin login
     */
    public function loginAction(): Response
    {
        return $this->forward(LoginController::class.'::loginCheckAction');
    }
}
