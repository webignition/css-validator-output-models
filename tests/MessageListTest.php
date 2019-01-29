<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

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
}
