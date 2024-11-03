<?php

declare(strict_types=1);

namespace App\Configuration\Option;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use App\Configuration\Option\OptionInterface;
use App\Configuration\Option\AbstractOption;
use App\Exception\Option\EmptyCodeException;

#[CoversClass(OptionInterface::class)]
#[CoversClass(AbstractOption::class)]
class OptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $optionMock = $this
            ->getMockBuilder(AbstractOption::class)
            ->setConstructorArgs(['code'])
            ->getMock();

        self::assertInstanceOf(OptionInterface::class, $optionMock);
    }

    public function testConstructorException(): void
    {
        self::expectException(EmptyCodeException::class);

        $this
            ->getMockBuilder(AbstractOption::class)
            ->setConstructorArgs([''])
            ->getMock();
    }

    public function testGetCode(): void
    {
        $optionCode = 'code';

        $optionMock = $this
            ->getMockBuilder(AbstractOption::class)
            ->setConstructorArgs([$optionCode])
            ->getMock();
        $optionMock->method('getCode')->willReturn($optionCode);

        self::assertEquals($optionCode, $optionMock->getCode());
        self::assertIsString($optionMock->getCode());
    }

    public function testGetValue(): void
    {
        $optionCode = 'code';

        $optionMock = $this
            ->getMockBuilder(AbstractOption::class)
            ->setConstructorArgs([$optionCode])
            ->getMock();

        self::assertNull($optionMock->getValue());


        $optionCode2 = 'code2';
        $optionValue2 = 'value';

        $optionMock = $this
            ->getMockBuilder(AbstractOption::class)
            ->setConstructorArgs([$optionCode2, $optionValue2])
            ->getMock();
        $optionMock->method('getValue')->willReturn($optionValue2);

        self::assertIsString($optionMock->getValue());
        self::assertEquals($optionValue2, $optionMock->getValue());


        $optionCode3 = 'code3';
        $optionValue3 = null;

        $optionMock = $this
            ->getMockBuilder(AbstractOption::class)
            ->setConstructorArgs([$optionCode3, $optionValue3])
            ->getMock();
        $optionMock->method('getValue')->willReturn($optionValue3);

        self::assertNull($optionMock->getValue());
        self::assertEquals($optionValue3, $optionMock->getValue());


        $optionCode4 = 'code4';
        $optionValue4 = 1;

        $optionMock = $this
            ->getMockBuilder(AbstractOption::class)
            ->setConstructorArgs([$optionCode4, $optionValue4])
            ->getMock();
        $optionMock->method('getValue')->willReturn($optionValue4);

        self::assertIsInt($optionMock->getValue());
        self::assertEquals($optionValue4, $optionMock->getValue());
    }
}
