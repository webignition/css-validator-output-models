<?php

namespace webignition\CssValidatorOutput\Model;

class ObservationResponse
{
    private $ref;
    private $dateTime;
    private $messages;

    public function __construct(string $ref, \DateTime $dateTime, MessageList $messages)
    {
        $this->ref = $ref;
        $this->dateTime = $dateTime;
        $this->messages = $messages;
    }

    public function getRef(): string
    {
        return $this->ref;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function getMessages(): MessageList
    {
        return $this->messages;
    }
}
