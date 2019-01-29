<?php

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\InfoMessage;
use webignition\ValidatorMessage\MessageInterface;

class InfoMessageTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $message = 'message';
        $description = 'description';

        $infoMessage = new InfoMessage($message, $description);

        $this->assertEquals(MessageInterface::TYPE_INFO, $infoMessage->getType());
        $this->assertTrue($infoMessage->isInfo());
        $this->assertFalse($infoMessage->isError());
        $this->assertFalse($infoMessage->isWarning());
        $this->assertEquals($message, $infoMessage->getMessage());
        $this->assertEquals($description, $infoMessage->getDescription());
    }

    public function testJsonSerialize()
    {
        $message = 'message';
        $description = 'description';

        $infoMessage = new InfoMessage($message, $description);

        $this->assertEquals(
            [
                MessageInterface::KEY_TYPE => MessageInterface::TYPE_INFO,
                MessageInterface::KEY_MESSAGE => $message,
                InfoMessage::KEY_DESCRIPTION => $description,
            ],
            $infoMessage->jsonSerialize()
        );
    }

    public function testWithTitle()
    {
        $originalMessage = 'original message';
        $updatedMessage = 'updated message';

        $infoMessage = new InfoMessage($originalMessage, '');
        $this->assertEquals($originalMessage, $infoMessage->getMessage());

        $updatedInfoMessage = $infoMessage->withMessage($updatedMessage);
        $this->assertEquals($updatedMessage, $updatedInfoMessage->getMessage());
        $this->assertEquals($originalMessage, $infoMessage->getMessage());
        $this->assertNotSame($updatedInfoMessage, $infoMessage);
    }
}
