<?php

namespace webignition\CssValidatorOutput\Model;

interface OutputInterface
{
    public function isIncorrectUsageOutput(): bool;
    public function isExceptionOutput(): bool;
    public function isValidationOutput(): bool;
}
