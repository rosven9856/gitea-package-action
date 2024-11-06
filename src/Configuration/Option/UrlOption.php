<?php

declare(strict_types=1);

namespace App\Configuration\Option;

use App\Exception\Option\ValidateException;

class UrlOption extends AbstractOption
{
    public function validate()
    {
        if (empty($this->getValue())) {
            throw new ValidateException("Url is required");
        }
    }
}
