<?php

namespace webignition\CssValidatorOutput\Model;

use webignition\ValidatorMessage\AbstractMessage;

abstract class AbstractIssueMessage extends AbstractMessage implements \JsonSerializable
{
    const KEY_CONTEXT = 'context';
    const KEY_REF = 'ref';
    const KEY_LINE_NUMBER = 'line_number';

    private $ref;
    private $lineNumber;
    private $context;

    public function __construct(string $type, string $message, int $lineNumber, string $context, string $ref)
    {
        parent::__construct($type, $message);

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

    public function withLineNumber(int $lineNumber): self
    {
        $new = clone $this;
        $new->lineNumber = $lineNumber;

        return $new;
    }

    public function withContext(string $context): self
    {
        $new = clone $this;
        $new->context = $context;

        return $new;
    }

    public function withRef(string $ref): self
    {
        $new = clone $this;
        $new->ref = $ref;

        return $new;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            self::KEY_CONTEXT => $this->context,
            self::KEY_REF => $this->ref,
            self::KEY_LINE_NUMBER => $this->lineNumber,
        ]);
    }
}
