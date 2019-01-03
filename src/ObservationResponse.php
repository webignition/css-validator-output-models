<?php

namespace webignition\CssValidatorOutput\Model;

class ObservationResponse implements MessageListViewerInterface
{
    private $ref;
    private $dateTime;

    /**
     * @var AbstractMessage[]
     */
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

    public function addMessage(AbstractMessage $message)
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

    public function getRef(): string
    {
        return $this->ref;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    /**
     * @return AbstractMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return ErrorMessage[]
     */
    public function getErrors(): array
    {
        return $this->getMessagesOfType(AbstractMessage::TYPE_ERROR);
    }

    /**
     * @param string $ref
     *
     * @return ErrorMessage[]
     */
    public function getErrorsByRef(string $ref): array
    {
        $errors = $this->getErrors();
        $errorsByRef = [];

        foreach ($errors as $error) {
            if ($ref === $error->getRef()) {
                $errorsByRef[] = $error;
            }
        }

        return $errorsByRef;
    }

    /**
     * @return WarningMessage[]
     */
    public function getWarnings(): array
    {
        return $this->getMessagesOfType(AbstractMessage::TYPE_WARNING);
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

    public function getMessageCount(): int
    {
        return count($this->messages);
    }

    private function getMessagesOfType(string $type): array
    {
        $messages = [];

        foreach ($this->messages as $message) {
            if ($type === $message->getType()) {
                $messages[] = $message;
            }
        }

        return $messages;
    }
}
