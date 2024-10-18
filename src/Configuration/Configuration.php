<?php

declare(strict_types=1);

namespace App\Configuration;

use App\Casting\MapperType;
use App\Casting\Type\Type;
use App\Exception\Casting\TypeNotFountException;

final class Configuration
{
    public array $options = [];

    public function __construct()
    {
        return $this;
    }

    public function set(string $key, mixed $value): self
    {
        $this->options[$key] = $value;

        return $this;
    }

    public function get(string $key): mixed
    {
        return $this->options[$key] ?? null;
    }

    /**
     * @throws TypeNotFountException
     */
    public function getCastingType(string $key, string $type): Type
    {
        return (new MapperType($type))->create($this->get($key));
    }
}
