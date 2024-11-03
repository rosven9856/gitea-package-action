<?php

declare(strict_types=1);

namespace App\Configuration;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use App\Configuration\Option\OptionInterface;
use App\Exception\Option\NotFoundException;

#[CoversClass(Configuration::class)]
final class ConfigurationTest extends TestCase
{
    public function testGetOptions(): void
    {
        $configuration = new Configuration();

        self::assertIsArray(
            $configuration->getOptions()
        );
    }

    public function testOptionMethod(): void
    {
        $optionMock = $this->getMockBuilder(OptionInterface::class)->getMock();

        $configuration = new Configuration();
        $configuration->option($optionMock);

        self::assertCount(1, $configuration->getOptions());
        self::assertInstanceOf(OptionInterface::class, $configuration->getOptions()[0]);
    }

    public function testGetOptionByCode(): void
    {
        $optionCode = 'code';

        $optionMock = $this->getMockBuilder(OptionInterface::class)->getMock();
        $optionMock->method('getCode')->willReturn($optionCode);

        $configuration = new Configuration();
        $configuration->option($optionMock);

        self::assertInstanceOf(OptionInterface::class, $configuration->getOptionByCode($optionCode));
    }

    public function testGetOptionByCodeException(): void
    {
        self::expectException(NotFoundException::class);

        $optionCode = 'code';

        $optionMock = $this->getMockBuilder(OptionInterface::class)->getMock();
        $optionMock->method('getCode')->willReturn($optionCode);

        $configuration = new Configuration();
        $configuration->option($optionMock);

        $configuration->getOptionByCode('code_not_found');
    }
}
