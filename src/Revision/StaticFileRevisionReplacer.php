<?php

namespace Atakde\Deploiy\Revision;

class StaticFileRevisionReplacer
{
    public function __construct(
        private array $paths,
        private string $revisionPlaceholder,
        private array $revisionReplecableExtensions,
        private array $revisionReplaceSkipPaths,
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
        $this->processDirectory($path);
    }

    private function processDirectory(string $path): void
    {
        $extensions = $this->revisionReplecableExtensions;
        $skipPaths = $this->revisionReplaceSkipPaths;

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

                $this->processDirectory($file);
            } else {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                if (!in_array($extension, $extensions)) {
                    echo "Skipping file: $file\n";
                    continue;
                }

                $content = file_get_contents($file);
                if (empty($content) || strpos($content, $this->revisionPlaceholder) === false) {
                    echo "Skipping file (no need to update): $file\n";
                    continue;
                }

                $content = str_replace($this->revisionPlaceholder, $revision, $content);
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
