<?php

namespace webignition\CssValidatorOutput\Model;

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
     * @return WarningMessage|ErrorMessage|null
     */
    public static function createFromDOMElement(\DOMElement $messageElement): ?AbstractMessage
    {
        $type = $messageElement->getAttribute('type');

        if (AbstractMessage::TYPE_ERROR !== $type && AbstractMessage::TYPE_WARNING !== $type) {
            return null;
        }

        $contextNode = $messageElement->getElementsByTagName('context')->item(0);

        return self::createIssueMessageFromArray([
            self::ARRAY_KEY_TYPE => $type,
            self::ARRAY_KEY_TITLE => trim(
                $messageElement->getElementsByTagName('title')->item(0)->nodeValue
            ),
            self::ARRAY_KEY_CONTEXT => $contextNode->nodeValue,
            self::ARRAY_KEY_REF => $messageElement->getAttribute('ref'),
            self::ARRAY_KEY_LINE_NUMBER => (int) $contextNode->getAttribute('line'),
        ]);
    }

    public static function createWarningFromError(ErrorMessage $error): WarningMessage
    {
        /* @var WarningMessage $warningMessage */
        $warningMessage = self::createIssueMessageFromArray([
            self::ARRAY_KEY_TYPE => AbstractIssueMessage::TYPE_WARNING,
            self::ARRAY_KEY_TITLE => $error->getTitle(),
            self::ARRAY_KEY_CONTEXT => $error->getContext(),
            self::ARRAY_KEY_REF => $error->getRef(),
            self::ARRAY_KEY_LINE_NUMBER => $error->getLineNumber()
        ]);

        return $warningMessage;
    }

    private static function createIssueMessageFromArray(array $messageData): ?AbstractIssueMessage
    {
        $type = $messageData[self::ARRAY_KEY_TYPE];
        $title  = $messageData[self::ARRAY_KEY_TITLE];
        $context  = $messageData[self::ARRAY_KEY_CONTEXT];
        $ref  = $messageData[self::ARRAY_KEY_REF];
        $lineNumber  = $messageData[self::ARRAY_KEY_LINE_NUMBER];

        if (AbstractIssueMessage::TYPE_ERROR === $type) {
            return new ErrorMessage($title, $lineNumber, $context, $ref);
        }

        $level = 0;

        return new WarningMessage($title, $lineNumber, $context, $ref, $level);
    }
}
