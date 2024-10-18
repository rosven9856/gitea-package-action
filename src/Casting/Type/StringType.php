<?php

declare(strict_types=1);

namespace App\Casting\Type;

final class StringType extends AbstractType
{
    #[\Override]
    public function getAsCasting(): string
    {
        return (string) $this->getValue();
    }
}
