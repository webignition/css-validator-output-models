<?php

namespace webignition\CssValidatorOutput\Model;

class IncorrectUsageOutput implements OutputInterface
{
    public function isIncorrectUsageOutput(): bool
    {
        return true;
    }

    public function isExceptionOutput(): bool
    {
        return false;
    }

    public function isValidationOutput(): bool
    {
        return false;
    }
}
