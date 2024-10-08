<?php

namespace Atakde\Deploiy;

use Atakde\Deploiy\Parser\ParserInterface;
use Atakde\Deploiy\Runner\RunnerInterface;

class Deployer
{
    public function __construct(
        private ParserInterface $parser,
        private RunnerInterface $runner
    ) {
    }

    public function deploy(string $content): void
    {
        $deployModel = $this->parser->parse($content);
        $this->runner->run($deployModel);
    }

    public function deployWithConfigPath(string $configPath): void
    {
        $content = file_get_contents($configPath);
        $this->deploy($content);
    }
}
