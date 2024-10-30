<?php

namespace App\Configuration\Option;

interface OptionInterface
{
    public function getName(): string;
    public function getValue(): mixed;
    public function isRequired(): self;
    public function getIsRequired(): bool;
    public function cannotBeEmpty(): self;
    public function getIsCannotBeEmpty(): bool;
    public function defaultValue(mixed $value): self;
    public function getDefaultValue(): mixed;
    public function validate();
}
