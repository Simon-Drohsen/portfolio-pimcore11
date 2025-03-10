<?php

namespace App\Command;

use Elasticsearch\Client;
use Exception;
use Pimcore\Model\DataObject\Blog;
use Pimcore\Model\DataObject\News;
use Pimcore\Model\DataObject\Project;
use Elasticsearch\ClientBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Response;

class ElasticsearchSyncCommand extends Command
{
    protected static $defaultName = 'app:elasticsearch:sync';
    private Client $client;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = ClientBuilder::create()->setHosts([$_ENV['ELASTICSEARCH_HOST']])->build();
        $ping = $this->client->ping();

        if (!$ping) {
            throw new Exception("Elasticsearch ist nicht erreichbar.");
        }
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Synchronisiert alle Blog-, News- und Projekt-Objekte mit Elasticsearch.')
            ->setHelp('Dieser Befehl lädt alle vorhandenen Blog-, News- und Projekt-Datenobjekte in Elasticsearch.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Elasticsearch Synchronisation gestartet');

        $objectTypes = [
            'blog' => Blog::class,
            'news' => News::class,
            'project' => Project::class
        ];

        $totalObjects = 0;
        $bulkParams = ['body' => []];

        foreach ($objectTypes as $index => $class) {
            $objectList = new ($class . '\Listing')();

            $total = $objectList->getTotalCount();
            $totalObjects += $total;

            $io->note("Index: {$index}, Gefundene Objekte: {$total}");

            if ($total > 0) {
                foreach ($objectList->load() as $object) {
                    $io->text("Lade: " . $object->getTitle());
                }
            }

            if ($total === 0) {
                continue;
            }

            foreach ($objectList->load() as $object) {
                $bulkParams['body'][] = [
                    'index' => [
                        '_index' => $index,
                        '_id' => $object->getId()
                    ]
                ];
                $bulkParams['body'][] = [
                    'title' => $object->getTitle(),
                    'content' => $object->getContent(),
                    'slug' => $object->getSlug(),
                ];
            }
        }

        if (!empty($bulkParams['body'])) {
            try {
                $this->client->bulk($bulkParams);
                $io->success("{$totalObjects} Objekte erfolgreich in Elasticsearch hochgeladen!");
            } catch (Exception $e) {
                $io->error("Fehler beim Hochladen: " . $e->getMessage());
                return Command::FAILURE;
            }
        } else {
            $io->warning('Keine Objekte gefunden.');
        }

        return Command::SUCCESS;
    }
}
