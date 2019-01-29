<?php

namespace webignition\CssValidatorOutput\Model;

use webignition\ValidatorMessage\MessageInterface;

class MessageFactory
{
    const ARRAY_KEY_TYPE = 'type';
    const ARRAY_KEY_TITLE = 'title';
    const ARRAY_KEY_CONTEXT = 'context';
    const ARRAY_KEY_REF = 'ref';
    const ARRAY_KEY_LINE_NUMBER = 'line_number';

    /**
     * @param \DOMElement $messageElement
     *
     * @return WarningMessage|ErrorMessage|MessageInterface|null
     */
    public static function createFromDOMElement(\DOMElement $messageElement): ?MessageInterface
    {
        $type = $messageElement->getAttribute('type');

        if (MessageInterface::TYPE_ERROR !== $type && MessageInterface::TYPE_WARNING !== $type) {
            return null;
        }

        $contextElement = $messageElement->getElementsByTagName('context')->item(0);
        if (!$contextElement instanceof \DOMElement) {
            return null;
        }

        $titleElement = $messageElement->getElementsByTagName('title')->item(0);
        if (!$titleElement instanceof \DOMElement) {
            return null;
        }

        return self::createIssueMessageFromArray([
            self::ARRAY_KEY_TYPE => $type,
            self::ARRAY_KEY_TITLE => trim($titleElement->nodeValue),
            self::ARRAY_KEY_CONTEXT => $contextElement->nodeValue,
            self::ARRAY_KEY_REF => $messageElement->getAttribute('ref'),
            self::ARRAY_KEY_LINE_NUMBER => (int) $contextElement->getAttribute('line'),
        ]);
    }

    public static function createWarningFromError(ErrorMessage $error): WarningMessage
    {
        $warningMessage = self::createWarningMessageFromArray([
            self::ARRAY_KEY_TYPE => AbstractIssueMessage::TYPE_WARNING,
            self::ARRAY_KEY_TITLE => $error->getMessage(),
            self::ARRAY_KEY_CONTEXT => $error->getContext(),
            self::ARRAY_KEY_REF => $error->getRef(),
            self::ARRAY_KEY_LINE_NUMBER => $error->getLineNumber()
        ]);

        return $warningMessage;
    }

    /**
     * @param array $messageData
     *
     * @return AbstractIssueMessage
     */
    private static function createIssueMessageFromArray(array $messageData): AbstractIssueMessage
    {
        if (AbstractIssueMessage::TYPE_ERROR === $messageData[self::ARRAY_KEY_TYPE]) {
            return self::createErrorMessageFromArray($messageData);
        }

        return self::createWarningMessageFromArray($messageData);
    }

    private static function createErrorMessageFromArray(array $messageData): ErrorMessage
    {
        $title  = $messageData[self::ARRAY_KEY_TITLE];
        $lineNumber  = $messageData[self::ARRAY_KEY_LINE_NUMBER];
        $context  = $messageData[self::ARRAY_KEY_CONTEXT];
        $ref  = $messageData[self::ARRAY_KEY_REF];

        return new ErrorMessage($title, $lineNumber, $context, $ref);
    }

    private static function createWarningMessageFromArray(array $messageData): WarningMessage
    {
        $title  = $messageData[self::ARRAY_KEY_TITLE];
        $lineNumber  = $messageData[self::ARRAY_KEY_LINE_NUMBER];
        $context  = $messageData[self::ARRAY_KEY_CONTEXT];
        $ref  = $messageData[self::ARRAY_KEY_REF];
        $level = 0;

        return new WarningMessage($title, $lineNumber, $context, $ref, $level);
    }
}
