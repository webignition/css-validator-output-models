<?php

namespace webignition\CssValidatorOutput\Model;

abstract class AbstractMessage implements \JsonSerializable
{
    const KEY_TYPE = 'type';
    const KEY_TITLE = 'message';

    const TYPE_ERROR = 'error';
    const TYPE_WARNING = 'warning';
    const TYPE_INFO = 'info';

    private $type;
    private $title;

    public function __construct(string $type, string $title)
    {
        $this->type = $type;
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function withTitle(string $title): self
    {
        $new = clone $this;
        $new->title = $title;

        return $new;
    }

    public function isError(): bool
    {
        return self::TYPE_ERROR === $this->type;
    }

    public function isWarning(): bool
    {
        return self::TYPE_WARNING === $this->type;
    }

    public function isInfo(): bool
    {
        return self::TYPE_INFO === $this->type;
    }

    public function jsonSerialize(): array
    {
        return [
            self::KEY_TYPE => $this->type,
            self::KEY_TITLE => $this->title,
        ];
    }
}
