<?php

namespace Atakde\Deploiy\Models;

class DeployModel
{
    private array $config = [];
    private array $commands = [];

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->setCommands();
    }

    private function setCommands(): void
    {
        foreach ($this->config['commands'] as $key => $command) {
            $this->commands[] = new CommandModel($command['name'], $command['command']);
        }
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
