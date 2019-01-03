<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\AbstractIssueMessage;
use webignition\CssValidatorOutput\Model\AbstractMessage;
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
        $warning = new WarningMessage($title, $lineNumber, $context, $ref, $level);

        $this->assertEquals(WarningMessage::TYPE_WARNING, $warning->getType());
        $this->assertEquals($title, $warning->getTitle());
        $this->assertEquals($lineNumber, $warning->getLineNumber());
        $this->assertEquals($context, $warning->getContext());
        $this->assertEquals($ref, $warning->getRef());
        $this->assertFalse($warning->isError());
        $this->assertTrue($warning->isWarning());
        $this->assertFalse($warning->isInfo());
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

    public function testJsonSerialize()
    {
        $title = 'title';
        $lineNumber = 1;
        $context = '.foo';
        $ref = 'http://example.com';
        $level = 0;

        $warning = new WarningMessage($title, $lineNumber, $context, $ref, $level);

        $this->assertEquals(
            [
                AbstractIssueMessage::KEY_TYPE => AbstractMessage::TYPE_WARNING,
                AbstractIssueMessage::KEY_TITLE => $title,
                AbstractIssueMessage::KEY_CONTEXT => $context,
                AbstractIssueMessage::KEY_LINE_NUMBER => $lineNumber,
                AbstractIssueMessage::KEY_REF => $ref,
                WarningMessage::KEY_LEVEL => $level,
            ],
            $warning->jsonSerialize()
        );
    }

    public function testWithTitle()
    {
        $originalTitle = 'original title';
        $updatedTitle = 'updatedTitle';

        $warning = new WarningMessage($originalTitle, 0, '', '', 0);
        $this->assertEquals($originalTitle, $warning->getTitle());

        $updatedWarning = $warning->withTitle($updatedTitle);
        $this->assertEquals($updatedTitle, $updatedWarning->getTitle());
        $this->assertEquals($originalTitle, $warning->getTitle());
        $this->assertNotSame($updatedWarning, $warning);
    }

    public function testWithLineNumber()
    {
        $originalLineNumber = 1;
        $updatedLineNumber = 2;

        $warning = new WarningMessage('', $originalLineNumber, '', '', 0);
        $this->assertEquals($originalLineNumber, $warning->getLineNumber());

        $updatedWarning = $warning->withLineNumber($updatedLineNumber);
        $this->assertEquals($updatedLineNumber, $updatedWarning->getLineNumber());
        $this->assertEquals($originalLineNumber, $warning->getLineNumber());
        $this->assertNotSame($updatedWarning, $warning);
    }

    public function testWithContext()
    {
        $originalContext = 'original context';
        $updatedContext = 'updated context';

        $warning = new WarningMessage('', 0, $originalContext, '', 0);
        $this->assertEquals($originalContext, $warning->getContext());

        $updatedWarning = $warning->withContext($updatedContext);
        $this->assertEquals($updatedContext, $updatedWarning->getContext());
        $this->assertEquals($originalContext, $warning->getContext());
        $this->assertNotSame($updatedWarning, $warning);
    }

    public function testWithRef()
    {
        $originalRef = 'original ref';
        $updatedRef = 'updated ref';

        $warning = new WarningMessage('', 0, '', $originalRef, 0);
        $this->assertEquals($originalRef, $warning->getRef());

        $updatedWarning = $warning->withRef($updatedRef);
        $this->assertEquals($updatedRef, $updatedWarning->getRef());
        $this->assertEquals($originalRef, $warning->getRef());
        $this->assertNotSame($updatedWarning, $warning);
    }
}
