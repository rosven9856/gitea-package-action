<?php

declare(strict_types=1);

namespace App\Configuration\Option;

interface OptionInterface
{
    public function getCode(): string;
    public function getValue(): mixed;
    public function isRequired(): self;
    public function getIsRequired(): bool;
    public function cannotBeEmpty(): self;
    public function getIsCannotBeEmpty(): bool;
    public function defaultValue(mixed $value): self;
    public function getDefaultValue(): mixed;
    public function validate();
}
