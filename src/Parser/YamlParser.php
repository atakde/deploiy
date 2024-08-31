<?php

namespace Atakde\Deploiy\Parser;

use Atakde\Deploiy\Models\DeployerModel;
use Atakde\Deploiy\Models\DeployModel;
use Atakde\Deploiy\Models\EnvironmentModel;
use Atakde\Deploiy\Parser\ParserInterface;
use Symfony\Component\Yaml\Yaml;

class YamlParser implements ParserInterface
{
    public function parse(string $content): DeployerModel
    {
        $data = Yaml::parse($content);

        return new DeployerModel(
            $data['deploy']['appName'] ?? '',
            $data['deploy']['version'] ?? '',
            new EnvironmentModel(
                $data['deploy']['environment']['type'],
                $data['deploy']['environment']['host'],
                $data['deploy']['environment']['port'],
                $data['deploy']['environment']['deployPath'],
            ),
            new DeployModel($data['deploy']['preDeploy'] ?? []),
            new DeployModel($data['deploy']['postDeploy'] ?? [])
        );
    }
}
