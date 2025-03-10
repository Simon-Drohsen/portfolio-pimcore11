<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pimcore\Model\DataObject;
use Elasticsearch\ClientBuilder;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request): Response
    {
        $query = $request->query->get('q', '');

        try {
            $client = ClientBuilder::create()->setHosts([$_ENV['ELASTICSEARCH_HOST']])->build();
            $ping = $client->ping();

            if (!$ping) {
                throw new \Exception("Elasticsearch ist nicht erreichbar.");
            }
        } catch (\Exception $e) {
            return new Response("Fehlers: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $params = [
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            ['wildcard' => ['content' => '*' . $query . '*']],
                            ['wildcard' => ['title' => '*' . $query . '*']],
                        ],
                        'minimum_should_match' => 1
                    ]
                ]
            ]
        ];

        try {
            $response = $client->search($params);
            $results = $response['hits']['hits'] ?? [];
        } catch (\Exception $e) {
            return new Response("Elasticsearch-Fehler: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $objList = [];

        foreach ($results as $result) {
            $blog = DataObject\Blog::getBySlug($result['_source']['slug'])->getObjects();
            $news = DataObject\News::getBySlug($result['_source']['slug'])->getObjects();
            $project = DataObject\Project::getBySlug($result['_source']['slug'])->getObjects();

            if (count($blog) > 0) {
                $objList[] = $blog[0];
            }

            if (count($news) > 0) {
                $objList[] = $news[0];
            }

            if (count($project) > 0) {
                $objList[] = $project[0];
            }
        }

        return $this->render('search/results.html.twig', [
            'query' => $query,
            'results' => $objList
        ]);
    }
}
