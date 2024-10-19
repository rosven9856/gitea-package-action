<?php

declare(strict_types=1);

namespace App;

use App\Configuration\Configuration;

final class Action
{
    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    public function run(): void
    {
        $this->checkExtensions();
        $this->checkConfiguration();

        // $this->configuration->getCastingType('param', MapperType::STRING)->getAsCasting()
    }

    private function checkExtensions(): void
    {

    }

    private function checkConfiguration(): void
    {

    }
}
