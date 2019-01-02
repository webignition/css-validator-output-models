<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\AbstractMessage;
use webignition\CssValidatorOutput\Model\IssueMessage;

class IssueMessageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $type,
        string $title,
        int $lineNumber,
        string $context,
        string $ref,
        bool $expectedIsError,
        bool $expectedIsWarning
    ) {
        $issueMessage = new IssueMessage($type, $title, $lineNumber, $context, $ref);

        $this->assertEquals($type, $issueMessage->getType());
        $this->assertEquals($title, $issueMessage->getTitle());
        $this->assertEquals($lineNumber, $issueMessage->getLineNumber());
        $this->assertEquals($context, $issueMessage->getContext());
        $this->assertEquals($ref, $issueMessage->getRef());
        $this->assertEquals($expectedIsError, $issueMessage->isError());
        $this->assertEquals($expectedIsWarning, $issueMessage->isWarning());
        $this->assertFalse($issueMessage->isInfo());
    }

    public function createDataProvider(): array
    {
        return [
            'error, no ref' => [
                'type' => AbstractMessage::TYPE_ERROR,
                'title' => 'Parse Error',
                'lineNumber' => 1,
                'context' => 'unparseable',
                'ref' => '',
                'expectedIsError' =>  true,
                'expectedIsWarning' => false,
            ],
            'error, has ref' => [
                'type' => AbstractMessage::TYPE_ERROR,
                'title' => 'Parse Error',
                'lineNumber' => 2,
                'context' => 'unparseable',
                'ref' => 'http://example.com/',
                'expectedIsError' =>  true,
                'expectedIsWarning' => false,
            ],
            'warning, no ref' => [
                'type' => AbstractMessage::TYPE_ERROR,
                'title' => 'Property -webkit-box-sizing is an unknown vendor extension',
                'lineNumber' => 3,
                'context' => '',
                'ref' => '',
                'expectedIsError' =>  true,
                'expectedIsWarning' => false,
            ],
            'warning, has ref' => [
                'type' => AbstractMessage::TYPE_ERROR,
                'title' => 'Property -webkit-box-sizing is an unknown vendor extension',
                'lineNumber' => 4,
                'context' => '',
                'ref' => 'http://example.com/',
                'expectedIsError' =>  true,
                'expectedIsWarning' => false,
            ],
        ];
    }
}
