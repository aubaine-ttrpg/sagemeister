<?php

namespace App\Service;

class IconProvider
{
    private $iconDirectory;

    public function __construct(string $iconDirectory)
    {
        $this->iconDirectory = $iconDirectory;
    }

    public function getIcons(): array
    {
        return $this->scanDirectory($this->iconDirectory);
    }

    private function scanDirectory(string $directory, string $parent = ''): array
    {
        $icons = [];
        $items = scandir($directory);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $itemPath = $directory . '/' . $item;
            if (is_dir($itemPath)) {
                // Recursively scan subdirectories
                $icons = array_merge($icons, $this->scanDirectory($itemPath, $item));
            } elseif (pathinfo($item, PATHINFO_EXTENSION) === 'svg') {
                $iconName = $parent ? $parent . ':' . pathinfo($item, PATHINFO_FILENAME) : pathinfo($item, PATHINFO_FILENAME);
                $icons[$iconName] = $iconName; // Both key and value are the icon name in the desired format
            }
        }

        return $icons;
    }
}
