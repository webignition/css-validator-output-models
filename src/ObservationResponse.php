<?php

namespace webignition\CssValidatorOutput\Model;

class ObservationResponse
{
    private $ref;
    private $dateTime;
    private $messages;
    private $errorCount = 0;
    private $warningCount = 0;
    private $infoCount = 0;

    public function __construct(string $ref, \DateTime $dateTime)
    {
        $this->ref = $ref;
        $this->dateTime = $dateTime;
        $this->messages = [];
    }

    public function addMessage(IssueMessage $message)
    {
        $this->messages[] = $message;

        if ($message->isError()) {
            $this->errorCount++;
        } elseif ($message->isWarning()) {
            $this->warningCount++;
        } elseif ($message->isInfo()) {
            $this->infoCount++;
        }
    }

    public function getErrorCount(): int
    {
        return $this->errorCount;
    }

    public function getWarningCount(): int
    {
        return $this->warningCount;
    }

    public function getInfoCount(): int
    {
        return $this->infoCount;
    }
}
