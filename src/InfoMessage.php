<?php

namespace webignition\CssValidatorOutput\Model;

class InfoMessage extends AbstractMessage
{
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
}
