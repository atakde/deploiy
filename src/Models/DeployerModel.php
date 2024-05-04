<?php

namespace Atakde\Deploiy\Models;

class DeployerModel
{
    public function __construct(
        public string $appName,
        public string $version,
        public EnvironmentModel $environment,
        public DeployModel $preDeploy,
        public DeployModel $postDeploy
    ) {
    }

    public function getAppName(): string
    {
        return $this->appName;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getEnvironment(): EnvironmentModel
    {
        return $this->environment;
    }

    public function getPreDeploy(): DeployModel
    {
        return $this->preDeploy;
    }

    public function getPostDeploy(): DeployModel
    {
        return $this->postDeploy;
    }
}
