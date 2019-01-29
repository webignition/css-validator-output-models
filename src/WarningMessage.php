<?php

namespace webignition\CssValidatorOutput\Model;

class WarningMessage extends AbstractIssueMessage implements \JsonSerializable
{
    const KEY_LEVEL = 'level';

    private $level;

    public function __construct(string $message, int $lineNumber, string $context, string $ref, int $level)
    {
        parent::__construct(self::TYPE_WARNING, $message, $lineNumber, $context, $ref);

        $this->level = $level;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function withLevel(int $level): self
    {
        $new = clone $this;
        $new->level = $level;

        return $new;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            self::KEY_LEVEL => $this->level,
        ]);
    }
}
