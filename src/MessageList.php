<?php

namespace webignition\CssValidatorOutput\Model;

use webignition\ValidatorMessage\MessageList as BaseMessageList;

class MessageList extends BaseMessageList
{
    public function getErrorsByRef(string $ref): array
    {
        $errors = $this->getErrors();
        $errorsByRef = [];

        foreach ($errors as $error) {
            if ($error instanceof  ErrorMessage && $ref === $error->getRef()) {
                $errorsByRef[] = $error;
            }
        }

        return $errorsByRef;
    }
}
