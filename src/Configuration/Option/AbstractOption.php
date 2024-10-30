<?php

namespace App\Configuration\Option;

abstract class AbstractOption implements OptionInterface
{
    protected string $name;
    protected mixed $value;
    protected bool $isRequired;
    protected bool $isCannotBeEmpty;
    protected mixed $defaultValue;


    public function __construct(string $name, mixed $value)
    {
        $this->name = $name;
        $this->value = $value;
        $this->isRequired = false;
        $this->isCannotBeEmpty = false;
        $this->defaultValue = null;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): mixed
    {
        return $this->value ?? $this->defaultValue;
    }

    public function isRequired(): self
    {
        $this->isRequired = true;
    }

    public function getIsRequired(): bool
    {
        return $this->isRequired;
    }

    public function cannotBeEmpty(): self
    {
        $this->isCannotBeEmpty = true;
    }

    public function getIsCannotBeEmpty(): bool
    {
        return $this->isCannotBeEmpty;
    }

    public function defaultValue(mixed $value): self
    {
        $this->defaultValue = $value;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    abstract public function validate();
}
