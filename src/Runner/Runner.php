<?php

namespace Atakde\Deploiy\Runner;

use Atakde\Deploiy\Models\DeployerModel;

class Runner implements RunnerInterface
{
    public function run(DeployerModel $deployerModel): void
    {
        echo "*** DEPLOY ***\n";
        $this->goToDeployPath($deployerModel->getEnvironment()->getDeployPath());
        $this->runPreDeploy($deployerModel);
        $this->runPostDeploy($deployerModel);
        echo "*** END DEPLOY ***\n";
    }

    public function runPreDeploy(DeployerModel $deployerModel): void
    {
        echo "*** PRE DEPLOY ***\n";

        if (empty($deployerModel->preDeploy->getCommands())) {
            return;
        }

        foreach ($deployerModel->preDeploy->getCommands() as $command) {
            echo "Running command: {$command->getName()}\n";
            echo "Command: {$command->getCommand()}\n";
            echo "Command executed\n";

            $response = $this->runCommand($command->getCommand());
            if ($response !== null) {
                echo "Response: $response\n";
            }
        }

        echo "*** END PRE DEPLOY COMMAND ***\n";
    }

    private function goToDeployPath(string $path): void
    {
        // check path ends with a slash
        if (substr($path, -1) !== '/') {
            $path .= '/';
        }

        echo "Going to deploy path: $path\n";
        chdir($path);

        $currentPath = getcwd() . '/';
        if ($currentPath === $path) {
            echo "Current path: $path\n";
        } else {
            throw new \Exception("Error: Could not change to path $path");
        }
    }

    private function runCommand(string $command): ?string
    {
        try {
            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);
            if ($returnVar !== 0) {
                echo "Error: Command '$command' failed with exit code $returnVar\n";
                return null;
            }
            return implode("\n", $output);
        } catch (\Exception $e) {
            echo "Error: {$e->getMessage()}\n";
            return null;
        }
    }

    public function runPostDeploy(DeployerModel $deployerModel): void
    {
        echo "*** POST DEPLOY ***\n";

        if (empty($deployerModel->postDeploy->getCommands())) {
            return;
        }

        foreach ($deployerModel->postDeploy->getCommands() as $command) {
            echo "Running command: {$command->getName()}\n";
            echo "Command: {$command->getCommand()}\n";
            echo "Command executed\n";

            $response = $this->runCommand($command->getCommand());
            if ($response !== null) {
                echo "Response: $response\n";
            }
        }

        echo "*** END POST DEPLOY COMMAND ***\n";
    }
}
