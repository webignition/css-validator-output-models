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
        $this->messages[$message->getHash()] = $message;

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
        return $this->map(
            function (MessageList &$messageList, AbstractMessage $message) use ($mutator) {
                $messageList->addMessage($mutator($message));
            }
        );
    }

    public function filter(callable $matcher): MessageList
    {
        return $this->map(
            function (MessageList &$messageList, AbstractMessage $message) use ($matcher) {
                if ($matcher($message)) {
                    $messageList->addMessage($message);
                }
            }
        );
    }

    public function contains(AbstractMessage $message): bool
    {
        return array_key_exists($message->getHash(), $this->messages);
    }

    private function map(callable $callable): MessageList
    {
        $messageList = new MessageList();
        $messages = $this->messages;

        foreach ($messages as $message) {
            $callable($messageList, $message);
        }

        return $messageList;
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
