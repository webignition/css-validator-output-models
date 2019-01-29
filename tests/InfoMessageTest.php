<?php

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\AbstractMessage;
use webignition\CssValidatorOutput\Model\InfoMessage;

class InfoMessageTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $title = 'title';
        $description = 'description';

        $infoMessage = new InfoMessage($title, $description);

        $this->assertEquals(AbstractMessage::TYPE_INFO, $infoMessage->getType());
        $this->assertTrue($infoMessage->isInfo());
        $this->assertFalse($infoMessage->isError());
        $this->assertFalse($infoMessage->isWarning());
        $this->assertEquals($title, $infoMessage->getMessage());
        $this->assertEquals($description, $infoMessage->getDescription());
    }

    public function testJsonSerialize()
    {
        $title = 'title';
        $description = 'description';

        $infoMessage = new InfoMessage($title, $description);

        $this->assertEquals(
            [
                AbstractMessage::KEY_TYPE => AbstractMessage::TYPE_INFO,
                AbstractMessage::KEY_MESSAGE => $title,
                InfoMessage::KEY_DESCRIPTION => $description,
            ],
            $infoMessage->jsonSerialize()
        );
    }

    public function testWithTitle()
    {
        $originalTitle = 'original title';
        $updatedTitle = 'updatedTitle';

        $infoMessage = new InfoMessage($originalTitle, '');
        $this->assertEquals($originalTitle, $infoMessage->getMessage());

        $updatedInfoMessage = $infoMessage->withMessage($updatedTitle);
        $this->assertEquals($updatedTitle, $updatedInfoMessage->getMessage());
        $this->assertEquals($originalTitle, $infoMessage->getMessage());
        $this->assertNotSame($updatedInfoMessage, $infoMessage);
    }
}
