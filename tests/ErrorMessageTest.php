<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\AbstractIssueMessage;
use webignition\CssValidatorOutput\Model\ErrorMessage;
use webignition\ValidatorMessage\MessageInterface;

class ErrorMessageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $message,
        int $lineNumber,
        string $context,
        string $ref
    ) {
        $error = new ErrorMessage($message, $lineNumber, $context, $ref);

        $this->assertEquals(ErrorMessage::TYPE_ERROR, $error->getType());
        $this->assertEquals($message, $error->getMessage());
        $this->assertEquals($lineNumber, $error->getLineNumber());
        $this->assertEquals($context, $error->getContext());
        $this->assertEquals($ref, $error->getRef());
        $this->assertTrue($error->isError());
        $this->assertFalse($error->isWarning());
        $this->assertFalse($error->isInfo());
    }

    public function createDataProvider(): array
    {
        return [
            'no ref' => [
                'message' => 'Parse Error',
                'lineNumber' => 1,
                'context' => 'unparseable',
                'ref' => '',
                'expectedIsError' =>  true,
                'expectedIsWarning' => false,
            ],
            'has ref' => [
                'message' => 'Parse Error',
                'lineNumber' => 2,
                'context' => 'unparseable',
                'ref' => 'http://example.com/',
                'expectedIsError' =>  true,
                'expectedIsWarning' => false,
            ],
        ];
    }

    public function testJsonSerialize()
    {
        $message = 'message';
        $lineNumber = 1;
        $context = '.foo';
        $ref = 'http://example.com';

        $error = new ErrorMessage($message, $lineNumber, $context, $ref);

        $this->assertEquals(
            [
                AbstractIssueMessage::KEY_TYPE => MessageInterface::TYPE_ERROR,
                AbstractIssueMessage::KEY_MESSAGE => $message,
                AbstractIssueMessage::KEY_CONTEXT => $context,
                AbstractIssueMessage::KEY_LINE_NUMBER => $lineNumber,
                AbstractIssueMessage::KEY_REF => $ref,
            ],
            $error->jsonSerialize()
        );
    }

    public function testWithMessage()
    {
        $originalMessage = 'original message';
        $updatedMessage = 'updated message';

        $error = new ErrorMessage($originalMessage, 0, '', '');
        $this->assertEquals($originalMessage, $error->getMessage());

        $updatedError = $error->withMessage($updatedMessage);
        $this->assertEquals($updatedMessage, $updatedError->getMessage());
        $this->assertEquals($originalMessage, $error->getMessage());
        $this->assertNotSame($updatedError, $error);
    }

    public function testWithLineNumber()
    {
        $originalLineNumber = 1;
        $updatedLineNumber = 2;

        $error = new ErrorMessage('', $originalLineNumber, '', '');
        $this->assertEquals($originalLineNumber, $error->getLineNumber());

        $updatedError = $error->withLineNumber($updatedLineNumber);
        $this->assertEquals($updatedLineNumber, $updatedError->getLineNumber());
        $this->assertEquals($originalLineNumber, $error->getLineNumber());
        $this->assertNotSame($updatedError, $error);
    }

    public function testWithContext()
    {
        $originalContext = 'original context';
        $updatedContext = 'updated context';

        $error = new ErrorMessage('', 0, $originalContext, '');
        $this->assertEquals($originalContext, $error->getContext());

        $updatedError = $error->withContext($updatedContext);
        $this->assertEquals($updatedContext, $updatedError->getContext());
        $this->assertEquals($originalContext, $error->getContext());
        $this->assertNotSame($updatedError, $error);
    }

    public function testWithRef()
    {
        $originalRef = 'original ref';
        $updatedRef = 'updated ref';

        $error = new ErrorMessage('', 0, '', $originalRef);
        $this->assertEquals($originalRef, $error->getRef());

        $updatedError = $error->withRef($updatedRef);
        $this->assertEquals($updatedRef, $updatedError->getRef());
        $this->assertEquals($originalRef, $error->getRef());
        $this->assertNotSame($updatedError, $error);
    }
}
