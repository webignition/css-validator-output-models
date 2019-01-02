<?php

namespace webignition\CssValidatorOutput\Model;

class ErrorMessage extends AbstractIssueMessage
{
    public function __construct(string $title, int $lineNumber, string $context, string $ref)
    {
        parent::__construct(self::TYPE_ERROR, $title, $lineNumber, $context, $ref);
    }
}
