<?php

declare(strict_types=1);

namespace App\Casting\Type;

use App\Casting\Casting;

abstract class AbstractType implements Type, Casting
{
    public function __construct(protected mixed $value) {}

    #[\Override]
    final public function getValue()
    {
        return $this->value;
    }

    #[\Override]
    abstract public function getAsCasting(): mixed;
}
