<?php

namespace webignition\CssValidatorOutput\Model;

use webignition\ValidatorMessage\AbstractMessage;

class InfoMessage extends AbstractMessage implements \JsonSerializable
{
    const KEY_DESCRIPTION = 'description';

    private $description;

    public function __construct(string $message, string $description)
    {
        parent::__construct(self::TYPE_INFO, $message);

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
