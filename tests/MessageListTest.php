<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\AbstractMessage;
use webignition\CssValidatorOutput\Model\ErrorMessage;
use webignition\CssValidatorOutput\Model\InfoMessage;
use webignition\CssValidatorOutput\Model\MessageList;
use webignition\CssValidatorOutput\Model\WarningMessage;

class MessageListTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MessageList
     */
    private $messageList;

    protected function setUp()
    {
        parent::setUp();

        $messages = [
            new ErrorMessage('error1', 1, '.error1', 'ref1'),
            new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
            new InfoMessage('info1', 'description'),
            new ErrorMessage('error2', 3, '.error2', 'ref1'),
            new WarningMessage('warning2', 4, '.warning2', 'ref2', 0),
            new ErrorMessage('error3', 5, '.error3', 'ref2'),
        ];

        $this->messageList = new MessageList();

        foreach ($messages as $message) {
            $this->messageList->addMessage($message);
        }
    }

    /**
     * @dataProvider addMessageDataProvider
     */
    public function testAddMessage(
        AbstractMessage $message,
        int $expectedErrorCount,
        int $expectedWarningCount,
        int $expectedInfoCount,
        int $expectedMessageCount
    ) {
        $this->messageList->addMessage($message);

        $this->assertEquals($expectedErrorCount, $this->messageList->getErrorCount());
        $this->assertEquals($expectedWarningCount, $this->messageList->getWarningCount());
        $this->assertEquals($expectedInfoCount, $this->messageList->getInfoCount());
        $this->assertEquals($expectedMessageCount, $this->messageList->getMessageCount());
    }

    public function addMessageDataProvider(): array
    {
        return [
            'add error' => [
                'message' => new ErrorMessage('error4', 1, '.error4', 'ref1'),
                'expectedErrorCount' => 4,
                'expectedWarningCount' => 2,
                'expectedInfoCount' => 1,
                'expectedMessageCount' => 7,
            ],
            'add warning' => [
                'message' => new WarningMessage('warning3', 1, '.warning3', 'ref1', 0),
                'expectedErrorCount' => 3,
                'expectedWarningCount' => 3,
                'expectedInfoCount' => 1,
                'expectedMessageCount' => 7,
            ],
            'add info' => [
                'message' => new InfoMessage('info2', 'description'),
                'expectedErrorCount' => 3,
                'expectedWarningCount' => 2,
                'expectedInfoCount' => 2,
                'expectedMessageCount' => 7,
            ],
        ];
    }

    public function testGetErrorsByRef()
    {
        $this->assertEquals(
            [
                new ErrorMessage('error1', 1, '.error1', 'ref1'),
                new ErrorMessage('error2', 3, '.error2', 'ref1'),
            ],
            $this->messageList->getErrorsByRef('ref1')
        );

        $this->assertEquals(
            [
                new ErrorMessage('error3', 5, '.error3', 'ref2'),
            ],
            $this->messageList->getErrorsByRef('ref2')
        );
    }

    public function testGetMessages()
    {
        $this->assertEquals(
            [
                new ErrorMessage('error1', 1, '.error1', 'ref1'),
                new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                new InfoMessage('info1', 'description'),
                new ErrorMessage('error2', 3, '.error2', 'ref1'),
                new WarningMessage('warning2', 4, '.warning2', 'ref2', 0),
                new ErrorMessage('error3', 5, '.error3', 'ref2'),
            ],
            $this->messageList->getMessages()
        );
    }

    public function testGetErrors()
    {
        $this->assertEquals(
            [
                new ErrorMessage('error1', 1, '.error1', 'ref1'),
                new ErrorMessage('error2', 3, '.error2', 'ref1'),
                new ErrorMessage('error3', 5, '.error3', 'ref2'),
            ],
            $this->messageList->getErrors()
        );
    }

    public function testGetWarnings()
    {
        $this->assertEquals(
            [
                new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                new WarningMessage('warning2', 4, '.warning2', 'ref2', 0),
            ],
            $this->messageList->getWarnings()
        );
    }
}
