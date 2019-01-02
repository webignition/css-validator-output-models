<?php

namespace webignition\CssValidatorOutput\Model;

class ExceptionOutput implements OutputInterface
{
    const TYPE_HTTP = 'http';
    const TYPE_CURL = 'curl';
    const TYPE_SSL_ERROR = 'ssl-error';
    const TYPE_UNKNOWN_MIME_TYPE = 'unknown-mime-type';
    const TYPE_UNKNOWN_FILE = 'unknown-file';
    const TYPE_UNKNOWN = 'unknown';

    private $type;
    private $code;

    public function __construct(string $type, int $code = null)
    {
        $this->type = $type;
        $this->code = $code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCode(): ?int
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
