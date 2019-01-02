<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\ErrorMessage;

class ErrorMessageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $title,
        int $lineNumber,
        string $context,
        string $ref
    ) {
        $issueMessage = new ErrorMessage($title, $lineNumber, $context, $ref);

        $this->assertEquals(ErrorMessage::TYPE_ERROR, $issueMessage->getType());
        $this->assertEquals($title, $issueMessage->getTitle());
        $this->assertEquals($lineNumber, $issueMessage->getLineNumber());
        $this->assertEquals($context, $issueMessage->getContext());
        $this->assertEquals($ref, $issueMessage->getRef());
        $this->assertTrue($issueMessage->isError());
        $this->assertFalse($issueMessage->isWarning());
        $this->assertFalse($issueMessage->isInfo());
    }

    public function createDataProvider(): array
    {
        return [
            'no ref' => [
                'title' => 'Parse Error',
                'lineNumber' => 1,
                'context' => 'unparseable',
                'ref' => '',
                'expectedIsError' =>  true,
                'expectedIsWarning' => false,
            ],
            'has ref' => [
                'title' => 'Parse Error',
                'lineNumber' => 2,
                'context' => 'unparseable',
                'ref' => 'http://example.com/',
                'expectedIsError' =>  true,
                'expectedIsWarning' => false,
            ],
        ];
    }
}
