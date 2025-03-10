<?php

namespace App\Controller;

use Pimcore\Model\DataObject\Blog;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog', name: 'app_blog_')]
class BlogController extends FrontendController
{
    #[Route('', name: 'list')]
    public function blogAction(Request $request): Response
    {
        $blogListing = new Blog\Listing();
        $blogs = $blogListing->getObjects();
        return $this->render('blog/blog.html.twig', [
            'blog_list' => $blogs,
        ]);
    }

    #[Route('/{slug}', name: 'detail')]
    public function blogDetailAction(Request $request, $slug): Response
    {
        $blog = Blog::getBySlug($slug);

        return $this->render('blog/blog_detail.html.twig', [
            'blog' => $blog->getObjects()[0],
        ]);
    }
}
