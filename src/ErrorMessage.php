<?php

namespace webignition\CssValidatorOutput\Model;

class ErrorMessage extends AbstractIssueMessage
{
    public function __construct(string $message, int $lineNumber, string $context, string $ref)
    {
        parent::__construct(self::TYPE_ERROR, $message, $lineNumber, $context, $ref);
    }
}
