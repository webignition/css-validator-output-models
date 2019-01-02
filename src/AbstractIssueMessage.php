<?php

namespace webignition\CssValidatorOutput\Model;

abstract class AbstractIssueMessage extends AbstractMessage
{
    private $ref;
    private $lineNumber;
    private $context;

    public function __construct(string $type, string $title, int $lineNumber, string $context, string $ref)
    {
        parent::__construct($type, $title);

        $this->lineNumber = $lineNumber;
        $this->context = $context;
        $this->ref = $ref;
    }

    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function getRef(): string
    {
        return $this->ref;
    }
}
