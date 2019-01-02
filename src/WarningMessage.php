<?php

namespace webignition\CssValidatorOutput\Model;

class WarningMessage extends AbstractIssueMessage implements \JsonSerializable
{
    const KEY_LEVEL = 'level';

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

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            self::KEY_LEVEL => $this->level,
        ]);
    }
}
