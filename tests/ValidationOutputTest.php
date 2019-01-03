<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\MessageList;
use webignition\CssValidatorOutput\Model\ObservationResponse;
use webignition\CssValidatorOutput\Model\Options;
use webignition\CssValidatorOutput\Model\ValidationOutput;

class ValidationOutputTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $options = new Options(
            false,
            'ucn',
            'en',
            2,
            'all',
            'css3'
        );

        $messageList = new MessageList();

        $observationResponse = new ObservationResponse('http://example.com/', new \DateTime(), $messageList);

        $output = new ValidationOutput($options, $observationResponse);

        $this->assertSame($options, $output->getOptions());
        $this->assertSame($observationResponse, $output->getObservationResponse());
        $this->assertSame($messageList, $observationResponse->getMessages());
        $this->assertSame($messageList, $output->getMessages());

        $this->assertTrue($output->isValidationOutput());
        $this->assertFalse($output->isIncorrectUsageOutput());
        $this->assertFalse($output->isExceptionOutput());
    }
}
