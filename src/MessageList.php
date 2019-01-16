<?php

namespace webignition\CssValidatorOutput\Model;

class MessageList
{
    /**
     * @var AbstractMessage[]
     */
    private $messages = [];

    private $errorCount = 0;
    private $warningCount = 0;
    private $infoCount = 0;

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

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getErrors(): array
    {
        return $this->getMessagesOfType(AbstractMessage::TYPE_ERROR);
    }

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

    public function mutate(callable $mutator): MessageList
    {
        $messages = $this->messages;
        $mutatedMessages = [];

        foreach ($messages as $message) {
            $mutatedMessages[] = $mutator($message);
        }

        $new = new MessageList();

        foreach ($mutatedMessages as $message) {
            $new->addMessage($message);
        }

        return $new;
    }

    public function filter(callable $matcher): MessageList
    {
        $messages = $this->messages;
        $filteredMessages = [];

        foreach ($messages as $message) {
            if ($matcher($message)) {
                $filteredMessages[] = $message;
            }
        }

        $new = new MessageList();

        foreach ($filteredMessages as $message) {
            $new->addMessage($message);
        }

        return $new;
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
