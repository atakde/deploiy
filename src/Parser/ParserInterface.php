<?php

namespace Atakde\Deploiy\Parser;

use Atakde\Deploiy\Models\DeployerModel;

interface ParserInterface
{
    public function parse(string $content): DeployerModel;
}
