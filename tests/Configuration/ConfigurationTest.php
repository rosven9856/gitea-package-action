<?php

declare(strict_types=1);

namespace App\Configuration;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Configuration::class)]
final class ConfigurationTest extends TestCase
{
    public function testUseConfiguration(): void
    {
        $configuration = new Configuration();
        $configuration->set('test-option', 'test-value');

        self::assertEquals(
            $configuration->get('test-option'),
            'test-value',
        );
    }
}
