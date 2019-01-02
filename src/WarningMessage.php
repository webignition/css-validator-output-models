<?php

namespace webignition\CssValidatorOutput\Model;

class WarningMessage extends AbstractIssueMessage
{
    private $level;

    public function __construct(string $title, int $lineNumber, string $context, string $ref, int $level)
    {
        parent::__construct(self::TYPE_WARNING, $title, $lineNumber, $context, $ref);

        $this->level = $level;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}
