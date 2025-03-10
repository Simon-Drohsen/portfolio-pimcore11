<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Model\DataObject\Project;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project', name: 'app_project_')]
class ProjectController extends FrontendController
{
    #[Route('', name: 'list')]
    public function projectAction(Request $request, PaginatorInterface $paginator): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 6;
        $projectQuery = (new Project\Listing())->load();

        $pagination = $paginator->paginate(
            $projectQuery,
            $page,
            $limit
        );

        return $this->render('default/list.html.twig', [
            'item_list' => $pagination,
        ]);
    }

    #[Route('/{slug}', name: 'detail')]
    public function projectDetailAction(Request $request, $slug): Response
    {
        $project = project::getBySlug($slug);

        return $this->render('default/detail.html.twig', [
            'item' => $project->getObjects()[0],
        ]);
    }
}
