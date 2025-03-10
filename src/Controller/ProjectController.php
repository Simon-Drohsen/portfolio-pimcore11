<?php

namespace App\Controller;

use Pimcore\Model\DataObject\Project;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project', name: 'app_project_')]
class ProjectController extends FrontendController
{
    #[Route('', name: 'list')]
    public function projectAction(Request $request): Response
    {
        $projectListing = new project\Listing();
        $project = $projectListing->getObjects();
        return $this->render('default/list.html.twig', [
            'item_list' => $project,
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
