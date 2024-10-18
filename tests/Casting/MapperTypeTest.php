<?php

declare(strict_types=1);

namespace App\Casting;

use App\Casting\Type\IntegerType;
use App\Casting\Type\StringType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MapperType::class)]
final class MapperTypeTest extends TestCase
{
    public function testGetStringType(): void
    {
        $type = (new MapperType(MapperType::STRING))->create('value');

        self::assertInstanceOf(
            $type,
            StringType::class,
        );
    }

    public function testGetIntegerType(): void
    {
        $type = (new MapperType(MapperType::INTEGER))->create(1);

        self::assertInstanceOf(
            $type,
            IntegerType::class,
        );
    }

    public function testGetIntType(): void
    {
        $type = (new MapperType(MapperType::INT))->create(1);

        self::assertInstanceOf(
            $type,
            IntegerType::class,
        );
    }
}
