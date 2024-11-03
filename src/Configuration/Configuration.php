<?php

declare(strict_types=1);

namespace App\Configuration;

use App\Configuration\Option\OptionInterface;
use App\Exception\Option\NotFoundException;

final class Configuration
{
    /**
     * @var array<OptionInterface>
     */
    public array $options = [];

    public function __construct()
    {
        return $this;
    }

    /**
     * @return OptionInterface[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function option(OptionInterface $option): self
    {
        $this->options[] = $option;

        return $this;
    }

    /**
     * @param string $code
     * @return OptionInterface
     * @throws NotFoundException
     */
    public function getOptionByCode(string $code): OptionInterface
    {
        foreach ($this->options as $option) {
            if ($option->getCode() === $code) {
                return $option;
            }
        }

        throw new NotFoundException(\sprintf('Option by code `%s` not found', $code));
    }
}
