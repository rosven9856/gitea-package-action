<?php

declare(strict_types=1);

namespace App\Configuration\Option;

use App\Exception\Option\EmptyCodeException;

abstract class AbstractOption implements OptionInterface
{
    protected string $code;
    protected mixed $value;
    protected bool $isRequired;
    protected bool $isCannotBeEmpty;
    protected mixed $defaultValue;


    public function __construct(string $code, mixed $value = null)
    {
        if (empty($code)) {
            throw new EmptyCodeException(\sprintf('Code in %s cannot be empty.', static::class));
        }

        $this->code = $code;
        $this->value = $value;
        $this->isRequired = false;
        $this->isCannotBeEmpty = false;
        $this->defaultValue = null;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getValue(): mixed
    {
        return $this->value ?? $this->defaultValue;
    }

    public function isRequired(): self
    {
        $this->isRequired = true;

        return $this;
    }

    public function getIsRequired(): bool
    {
        return $this->isRequired;
    }

    public function cannotBeEmpty(): self
    {
        $this->isCannotBeEmpty = true;

        return $this;
    }

    public function getIsCannotBeEmpty(): bool
    {
        return $this->isCannotBeEmpty;
    }

    public function defaultValue(mixed $value): self
    {
        $this->defaultValue = $value;

        return $this;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    abstract public function validate();
}
