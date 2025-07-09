<?php
// src/Controller/SkillTreeController.php
namespace App\Controller;

use App\Client\NotionClient;
use App\Enum\NotionDatabaseId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SkillTreeController extends AbstractController
{
    public function __construct(private NotionClient $notion) {}

    #[Route('/skilltree/{id}', name: 'skilltree')]
    public function index(string $id): Response
    {
        $treePage = $this->notion->retrievePage($id);

        $titleBlocks = $treePage['properties']['Name']['title'] ?? [];
        $treeName = count($titleBlocks)
            ? $titleBlocks[0]['plain_text']
            : $id;  // fallback to ID if no title set

        $filter = [
            'property' => 'Arbre',
            'relation' => ['contains' => $id],
        ];
        $sorts = [[
            'property'  => 'Position',
            'direction' => 'ascending',
        ]];

        $response   = $this->notion->queryDatabase(
            NotionDatabaseId::CAPACITY->value,
            [
                'filter'    => $filter,
                'sorts'     => $sorts,
                'page_size' => 100,
            ]
        );
        $capacities = $response['results'] ?? [];

        return $this->render('skilltree/index.html.twig', [
            'pageId'     => $id,
            'treeName'   => $treeName,
            'capacities' => $capacities,
        ]);
    }
}
