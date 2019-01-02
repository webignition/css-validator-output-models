<?php

namespace webignition\CssValidatorOutput\Model;

class ExceptionOutput implements OutputInterface
{
    const TYPE_HTTP = 'http';
    const TYPE_CURL = 'curl';

    private $type;
    private $code;

    public function __construct(string $type, int $code)
    {
        $this->type = $type;
        $this->code = $code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function isIncorrectUsageOutput(): bool
    {
        return false;
    }

    public function isExceptionOutput(): bool
    {
        return true;
    }

    public function isValidationOutput(): bool
    {
        return false;
    }
}
