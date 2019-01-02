<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\WarningMessage;

class WarningMessageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $title,
        int $lineNumber,
        string $context,
        string $ref,
        int $level
    ) {
        $issueMessage = new WarningMessage($title, $lineNumber, $context, $ref, $level);

        $this->assertEquals(WarningMessage::TYPE_WARNING, $issueMessage->getType());
        $this->assertEquals($title, $issueMessage->getTitle());
        $this->assertEquals($lineNumber, $issueMessage->getLineNumber());
        $this->assertEquals($context, $issueMessage->getContext());
        $this->assertEquals($ref, $issueMessage->getRef());
        $this->assertFalse($issueMessage->isError());
        $this->assertTrue($issueMessage->isWarning());
        $this->assertFalse($issueMessage->isInfo());
    }

    public function createDataProvider(): array
    {
        return [
            'no ref' => [
                'title' => 'Property -webkit-box-sizing is an unknown vendor extension',
                'lineNumber' => 3,
                'context' => '',
                'ref' => '',
                'level' => 0,
                'expectedIsError' =>  true,
                'expectedIsWarning' => false,
            ],
            'has ref' => [
                'title' => 'Property -webkit-box-sizing is an unknown vendor extension',
                'lineNumber' => 4,
                'context' => '',
                'ref' => 'http://example.com/',
                'level' => 0,
                'expectedIsError' =>  true,
                'expectedIsWarning' => false,
            ],
        ];
    }
}
