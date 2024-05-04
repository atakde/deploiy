<?php

namespace Atakde\Deploiy\Runner;

use Atakde\Deploiy\Models\DeployerModel;

interface RunnerInterface
{
    public function run(DeployerModel $deployModel): void;
    public function runPreDeploy(DeployerModel $deployModel): void;
    public function runPostDeploy(DeployerModel $deployModel): void;
}
