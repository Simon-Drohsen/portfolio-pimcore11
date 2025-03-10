<?php

namespace App\Controller;

use Pimcore\Bundle\AdminBundle\Controller\Admin\LoginController;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pimcore\Model\DataObject\Blog;
use Pimcore\Model\DataObject\News;
use Pimcore\Model\DataObject\Project;

class DefaultController extends FrontendController
{
    public function defaultAction(Request $request): Response
    {
        $blogs = (new Blog\Listing())->getObjects();
        $news = (new News\Listing())->getObjects();
        $project = (new Project\Listing())->getObjects();

        return $this->render('default/default.html.twig', [
            'blog_list' => $blogs,
            'news_list' => $news,
            'project_list' => $project,
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
