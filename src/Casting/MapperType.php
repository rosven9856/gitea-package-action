<?php

declare(strict_types=1);

namespace App\Casting;

use App\Casting\Type\IntegerType;
use App\Casting\Type\StringType;
use App\Casting\Type\Type;
use App\Exception\Casting\TypeNotFountException;

final readonly class MapperType
{
    public const string STRING = 'string';
    public const string INTEGER = 'integer';
    public const string INT = 'int';
    private const array MAP = [
        self::STRING => StringType::class,
        self::INTEGER => IntegerType::class,
        self::INT => IntegerType::class,
    ];

    private string $class;

    /**
     * @throws TypeNotFountException
     */
    public function __construct(string $type)
    {
        if (!isset(self::MAP[$type])) {
            throw new TypeNotFountException(
                \sprintf('Casting type "%s" is not found.', $type),
            );
        }

        $this->class = self::MAP[$type];
    }

    /**
     * @return mixed
     */
    public function create(mixed $value): Type
    {
        return new $this->class($value);
    }
}
