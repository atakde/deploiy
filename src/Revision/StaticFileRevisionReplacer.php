<?php

namespace Atakde\Deploiy\Revision;

class StaticFileRevisionReplacer
{
    public function __construct(
        private array $paths = [],
        private string $revisionAlgorithm = 'time'
    ) {
    }

    public function handleReplace(): void
    {
        foreach ($this->paths as $path) {
            $this->replaceRevision($path);
        }
    }

    private function replaceRevision(string $path): void
    {
        $extensions = ['php', 'html', 'txt'];
        $skipPaths = ['vendor', 'node_modules', 'public'];

        $this->processDirectory($path, $extensions, $skipPaths);
    }

    private function processDirectory(string $path, array $extensions, array $skipPaths): void
    {
        // Ensure path has a trailing slash
        if (!str_ends_with($path, '/')) {
            $path .= '/';
        }

        $files = glob($path . '*');
        $revision = $this->getRevision();

        echo "Revision: $revision\n";

        foreach ($files as $file) {
            if (is_dir($file)) {
                if (in_array(basename($file), $skipPaths)) {
                    echo "Skipping directory: $file\n";
                    continue;
                }

                $this->processDirectory($file, $extensions, $skipPaths);
            } else {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                if (!in_array($extension, $extensions)) {
                    echo "Skipping file: $file\n";
                    continue;
                }

                $content = file_get_contents($file);
                $content = str_replace('{REV}', $revision, $content);
                $resp = file_put_contents($file, $content);
                if ($resp === false) {
                    echo "Failed to write to file: $file\n";
                } else {
                    echo "Updated file: $file\n";
                }
            }
        }
    }

    private function getRevision(): string
    {
        return match ($this->revisionAlgorithm) {
            'time' => time(),
            'git-rev' => exec('git rev-parse HEAD'),
            default => time(),
        };
    }
}
