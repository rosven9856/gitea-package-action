<?php

declare(strict_types=1);

namespace App\Casting\Type;

final class IntegerType extends AbstractType
{
    #[\Override]
    public function getAsCasting(): int
    {
        return (int) $this->getValue();
    }
}
