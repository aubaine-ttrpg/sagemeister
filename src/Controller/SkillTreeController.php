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
        // 1) Fetch the tree page to get its name
        $treePage = $this->notion->retrievePage($id);
        $titleBlocks = $treePage['properties']['Name']['title'] ?? [];
        $skilltreeName = count($titleBlocks)
            ? $titleBlocks[0]['plain_text']
            : $id;

        // 2) Query capacities filtered & sorted
        $response = $this->notion->queryDatabase(
            NotionDatabaseId::CAPACITY->value,
            [
                'filter'    => ['property' => 'Arbre', 'relation' => ['contains' => $id]],
                'sorts'     => [['property' => 'Position', 'direction' => 'ascending']],
                'page_size' => 100,
            ]
        );
        $rawCaps = $response['results'] ?? [];

        // 3) Map IDs → Names
        $nameMap = [];
        foreach ($rawCaps as $cap) {
            $titles = $cap['properties']['Name']['title'] ?? [];
            $nameMap[$cap['id']] = $titles
                ? $titles[0]['plain_text']
                : 'UNKNOWN CAPACITY';
        }

        // 4) Build nodes array
        $nodes = [];
        foreach ($rawCaps as $cap) {
            // Position parsing
            $posText = $cap['properties']['Position']['rich_text'][0]['plain_text'] ?? '';
            if (!preg_match('/^\s*(\d+)\s*,\s*(\d+)\s*$/', $posText, $m)) {
                continue;
            }
            [, $row, $col] = $m;

            // Edges by Name lookup
            $edges = [];
            foreach ($cap['properties']['Prérequis (Capacité)']['relation'] as $rel) {
                if (isset($nameMap[$rel['id']])) {
                    $edges[] = $nameMap[$rel['id']];
                }
            }

            // Cover URL or fallback
            $cover = $cap['cover']['file']['url'] ?? 'https://placehold.co/150x150/png';

            // Description
            $descBlocks = $cap['properties']['Description']['rich_text'] ?? [];
            $description = $descBlocks
                ? $descBlocks[0]['plain_text']
                : '';

            // Cost and Type
            $cost = $cap['properties']['Coût (PC)']['number'] ?? 0;
            $type = $cap['properties']['Type']['select']['name'] ?? 'Unknown';

            $nodes[] = [
                'id'          => $cap['id'],
                'name'        => $nameMap[$cap['id']],
                'coverUrl'    => $cover,
                'description' => $description,
                'cost'        => $cost,
                'type'        => $type,
                'x'           => (int) $row,
                'y'           => (int) $col,
                'edges'       => $edges,
            ];
        }

        return $this->render('skilltree/index.html.twig', [
            'skilltreeName' => $skilltreeName,
            'nodes'         => $nodes,
        ]);
    }
}
