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
        $skilltreeName = count($titleBlocks)
            ? $titleBlocks[0]['plain_text']
            : $id;

        $response = $this->notion->queryDatabase(
            NotionDatabaseId::CAPACITY->value,
            [
                'filter'    => ['property' => 'Arbre', 'relation' => ['contains' => $id]],
                'sorts'     => [['property' => 'Position', 'direction' => 'ascending']],
                'page_size' => 100,
            ]
        );
        $rawCaps = $response['results'] ?? [];

        $nameMap = [];
        foreach ($rawCaps as $cap) {
            $titles = $cap['properties']['Name']['title'] ?? [];
            $nameMap[$cap['id']] = $titles
                ? $titles[0]['plain_text']
                : 'UNKNOWN CAPACITY';
        }

        $nodes = [];
        foreach ($rawCaps as $cap) {
            // parse Position: expect "row,column"
            $pos = $cap['properties']['Position']['rich_text'][0]['plain_text'] ?? '';
            if (!preg_match('/^\s*(\d+)\s*,\s*(\d+)\s*$/', $pos, $m)) {
                // skip invalid or missing
                continue;
            }
            [$_, $row, $col] = $m;

            $rels = $cap['properties']['Prérequis (Capacité)']['relation'] ?? [];
            $edges = [];
            foreach ($rels as $r) {
                if (isset($nameMap[$r['id']])) {
                    $edges[] = $nameMap[$r['id']];
                }
            }

            $nodes[] = [
                'name'  => $nameMap[$cap['id']],
                'x'     => (int) $col,
                'y'     => (int) $row,
                'edges' => $edges,
            ];
        }

        return $this->render('skilltree/index.html.twig', [
            'skilltree_name' => $skilltreeName,
            'nodes' => $nodes,
        ]);
    }
}
