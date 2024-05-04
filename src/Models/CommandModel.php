<?php

namespace Atakde\Deploiy\Models;

class CommandModel
{
    public function __construct(
        public string $name,
        public string $command
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCommand(): string
    {
        return $this->command;
    }
}
