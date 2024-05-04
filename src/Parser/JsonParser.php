<?php

namespace Atakde\Deploiy\Parser;

use Atakde\Deploiy\Models\DeployerModel;
use Atakde\Deploiy\Models\EnvironmentModel;
use Atakde\Deploiy\Models\DeployModel;
use Atakde\Deploiy\Models\SourceModel;
use Atakde\Deploiy\Parser\ParserInterface;

class JsonParser implements ParserInterface
{
    public function parse(string $content): DeployerModel
    {
        $data = json_decode($content, true);

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
