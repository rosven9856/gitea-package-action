<?php

declare(strict_types=1);

namespace App;

use App\Configuration\Configuration;
use App\Exception\RuntimeException;

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
    }

    private function checkExtensions(): void
    {
        if (!\extension_loaded('curl')) {
            throw new RuntimeException('CURL extension is not loaded');
        }
    }

    private function checkConfiguration(): void
    {
        $errors = $this->configuration->validate();

        if (\count($errors) > 0) {
            throw new RuntimeException(sprintf('Errors for configuration: %s', \implode(', ', $errors)));
        }
    }
}
