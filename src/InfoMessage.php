<?php

namespace webignition\CssValidatorOutput\Model;

class InfoMessage extends AbstractMessage implements \JsonSerializable
{
    const KEY_DESCRIPTION = 'description';

    private $description;

    public function __construct(string $title, string $description)
    {
        parent::__construct(self::TYPE_INFO, $title);

        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            self::KEY_DESCRIPTION => $this->description,
        ]);
    }
}
