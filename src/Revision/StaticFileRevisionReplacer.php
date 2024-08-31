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
        $extensions = ['php', 'html'];
        $skipPaths = ['vendor', 'node_modules', 'public'];
        $files = glob($path . '*');
        $revision = $this->getRevision();

        echo "Revision: $revision\n";
        var_dump($revision);
        var_dump($files);

        echo "Replacing revision in files\n";

        foreach ($files as $file) {
            if (is_dir($file) && in_array(basename($file), $skipPaths)) {
                echo "Skipping directory: $file\n";
                continue;
            }

            if (is_dir($file)) {
                echo "Directory: $file\n";
                $this->replaceRevision($file . '/');
                continue;
            }

            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($extension, $extensions)) {
                echo "Skipping file: $file\n";
                continue;
            }

            $content = file_get_contents($file);
            $content = str_replace('{REV}', $revision, $content);
            file_put_contents($file, $content);
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
