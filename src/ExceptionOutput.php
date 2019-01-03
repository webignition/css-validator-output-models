<?php

namespace webignition\CssValidatorOutput\Model;

class ExceptionOutput implements OutputInterface
{
    const TYPE_HTTP = 'http';
    const TYPE_CURL = 'curl';
    const TYPE_SSL_ERROR = 'ssl-error';
    const TYPE_UNKNOWN_CONTENT_TYPE = 'invalid-content-type';
    const TYPE_UNKNOWN_HOST  = 'unknown-host';
    const TYPE_UNKNOWN_FILE = 'unknown-file';
    const TYPE_UNKNOWN = 'unknown';

    private $type;
    private $subType;

    public function __construct(string $type, string $subType = '')
    {
        $this->type = $type;
        $this->subType = $subType;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSubType(): string
    {
        return $this->subType;
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

    public function __toString(): string
    {
        $string = $this->type;

        if (!empty($this->subType)) {
            $string .= ':' . $this->subType;
        }

        return $string;
    }
}
