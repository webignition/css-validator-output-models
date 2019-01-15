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

    public function testWithMessages()
    {
        $ref = 'http://example.com/';

        $originalObservationResponse = new ObservationResponse($ref, new \DateTime(), new MessageList());
        $updatedObservationResponse = $originalObservationResponse->withMessages(new MessageList());

        $this->assertNotSame($originalObservationResponse, $updatedObservationResponse);
        $this->assertEquals($ref, $originalObservationResponse->getRef());
        $this->assertEquals($ref, $updatedObservationResponse->getRef());
        $this->assertNotSame($originalObservationResponse->getDateTime(), $updatedObservationResponse->getDateTime());
        $this->assertNotSame($originalObservationResponse->getMessages(), $updatedObservationResponse->getMessages());
    }
}
