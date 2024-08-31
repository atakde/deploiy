<?php

namespace Atakde\Deploiy\Runner;

use Atakde\Deploiy\Models\DeployerModel;
use Atakde\Deploiy\Revision\StaticFileRevisionReplacer;

class Runner implements RunnerInterface
{
    public function run(DeployerModel $deployerModel): void
    {
        $deployPath = $deployerModel->getEnvironment()->getDeployPath();

        $this->goToDeployPath($deployPath);
        $this->runPreDeploy($deployerModel);
        $this->runPostDeploy($deployerModel);
    }

    public function runPreDeploy(DeployerModel $deployerModel): void
    {
        if (empty($deployerModel->preDeploy->getCommands())) {
            echo "No pre deploy commands\n";
            return;
        }

        foreach ($deployerModel->preDeploy->getCommands() as $command) {
            echo "Running command: {$command->getName()}\n";
            echo "Command: {$command->getCommand()}\n";
            echo "Command executed\n";

            $response = $this->runCommand($command->getCommand());
            if ($response !== null) {
                echo "[Response]: $response\n";
            }
        }
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
                var_dump($output);
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
        // is revision replace enabled
        if ($deployerModel->postDeploy->getConfig()['enableRevisionReplace']) {
            echo "[Revision Replacer]: Enabled\n";
            $this->runRevisionReplacer($deployerModel);
        }

        if (empty($deployerModel->postDeploy->getCommands())) {
            echo "No post deploy commands\n";
            return;
        }

        foreach ($deployerModel->postDeploy->getCommands() as $command) {
            echo "Running command: {$command->getName()}\n";
            echo "Command: {$command->getCommand()}\n";
            echo "Command executed\n";

            $response = $this->runCommand($command->getCommand());
            if ($response !== null) {
                echo "[Response]: $response\n";
            }
        }
    }

    public function runRevisionReplacer(DeployerModel $deployerModel): void
    {
        $path = $deployerModel->getEnvironment()->getDeployPath();
        $revisionPlaceholder = $deployerModel->postDeploy->getConfig()['revisionPlaceholder'] ?? '{REV}';
        $revisionReplecableExtensions = $deployerModel->postDeploy->getConfig()['revisionReplecableExtensions'] ?? [];
        $revisionReplaceSkipPaths = $deployerModel->postDeploy->getConfig()['revisionReplaceSkipPaths'] ?? [];
        $replacer = new StaticFileRevisionReplacer([$path], $revisionPlaceholder, $revisionReplecableExtensions, $revisionReplaceSkipPaths);
        $replacer->handleReplace();
    }
}
