<?php

namespace Atakde\Deploiy\Models;

class EnvironmentModel
{
    public function __construct(
        public string $type,
        public string $host,
        public int $port,
        public string $deployPath,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getDeployPath(): string
    {
        return $this->deployPath;
    }
}
