<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Client\NotionClient;
use App\Enum\NotionDatabaseId;

final class IndexController extends AbstractController
{
    public function __construct(private NotionClient $notion) {}

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $dbEnum     = NotionDatabaseId::TREE;
        $databaseId = $dbEnum->value;

        $database = $this->notion->retrieveDatabase($databaseId);

        $titleBlocks = $database['title'] ?? [];
        $dbName = count($titleBlocks) > 0
            ? $titleBlocks[0]['plain_text']
            : $dbEnum->name; // fallback to enum name

        $items = $this->notion->queryDatabase($databaseId);
        $pages = $items['results'] ?? [];
        
        return $this->render('index.html.twig', [
            'dbName' => $dbName,
            'pages'  => $pages,
        ]);
    }
}
