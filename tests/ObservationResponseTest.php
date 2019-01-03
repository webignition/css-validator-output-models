<?php
/** @noinspection PhpDocMissingThrowsInspection */
/** @noinspection PhpUnhandledExceptionInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\MessageList;
use webignition\CssValidatorOutput\Model\ObservationResponse;

class ObservationResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $ref = 'http://example.com/';
        $dateTime = new \DateTime();
        $messageList = new MessageList();

        $observationResponse = new ObservationResponse($ref, $dateTime, $messageList);

        $this->assertEquals($ref, $observationResponse->getRef());
        $this->assertSame($dateTime, $observationResponse->getDateTime());
        $this->assertSame($messageList, $observationResponse->getMessages());
    }
}
