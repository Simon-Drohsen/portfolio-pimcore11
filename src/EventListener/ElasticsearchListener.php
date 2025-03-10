<?php

namespace App\EventListener;

use Elasticsearch\Client;
use Pimcore\Event\Model\ElementEventInterface;
use Pimcore\Model\DataObject\Blog;
use Pimcore\Model\DataObject\News;
use Pimcore\Model\DataObject\Project;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Elasticsearch\ClientBuilder;
use Symfony\Component\HttpFoundation\Response;

class ElasticsearchListener implements EventSubscriberInterface
{
    private Client $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->setHosts([$_ENV['ELASTICSEARCH_HOST']])->build();
        $ping = $this->client->ping();

        if (!$ping) {
            throw new \Exception("Elasticsearch ist nicht erreichbar.");
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'pimcore.dataobject.postUpdate' => 'onObjectUpdate',
            'pimcore.dataobject.postDelete' => 'onObjectDelete',
        ];
    }

    public function onObjectUpdate(ElementEventInterface $event)
    {
        $object = $event->getElement();
        $params = [];

        if ($object instanceof Blog) {
            $params = [
                'body' => [
                    [
                        'index' => [
                            '_index' => 'blog',
                            '_id' => $object->getId()
                        ]
                    ],
                    [
                        'title' => $object->getTitle(),
                        'content' => $object->getContent(),
                        'slug' => $object->getSlug(),
                    ]
                ]
            ];
        }

        if ($object instanceof News) {
            $params = [
                'body' => [
                    [
                        'index' => [
                            '_index' => 'news',
                            '_id' => $object->getId()
                        ]
                    ],
                    [
                        'title' => $object->getTitle(),
                        'content' => $object->getContent(),
                        'slug' => $object->getSlug(),
                    ]
                ]
            ];
        }

        if ($object instanceof Project) {
            $params = [
                'body' => [
                    [
                        'index' => [
                            '_index' => 'project',
                            '_id' => $object->getId()
                        ]
                    ],
                    [
                        'title' => $object->getTitle(),
                        'content' => $object->getContent(),
                        'slug' => $object->getSlug(),
                    ]
                ]
            ];
        }

        if (empty($params)) {
            return;
        }

        $this->client->bulk($params);
    }

    public function onObjectDelete(ElementEventInterface $event)
    {
        $object = $event->getElement();
        $params = [];

        if ($object instanceof Blog) {
            $params = [
                'index' => 'blog',
                'id' => $object->getId(),
            ];
        }

        if ($object instanceof News) {
            $params = [
                'index' => 'news',
                'id' => $object->getId(),
            ];
        }

        if ($object instanceof Project) {
            $params = [
                'index' => 'project',
                'id' => $object->getId(),
            ];
        }

        if (empty($params)) {
            return;
        }

        $this->client->delete($params);
    }
}
