<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\ErrorMessage;
use webignition\CssValidatorOutput\Model\InfoMessage;
use webignition\CssValidatorOutput\Model\ObservationResponse;
use webignition\CssValidatorOutput\Model\Options;
use webignition\CssValidatorOutput\Model\ValidationOutput;
use webignition\CssValidatorOutput\Model\WarningMessage;

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

        $observationResponse = new ObservationResponse('http://example.com/', new \DateTime());

        $output = new ValidationOutput($options, $observationResponse);

        $this->assertSame($options, $output->getOptions());
        $this->assertSame($observationResponse, $output->getObservationResponse());

        $this->assertTrue($output->isValidationOutput());
        $this->assertFalse($output->isIncorrectUsageOutput());
        $this->assertFalse($output->isExceptionOutput());
    }

    public function testMessageListViewerMethods()
    {
        $options = new Options(
            false,
            'ucn',
            'en',
            2,
            'all',
            'css3'
        );

        $observationResponse = new ObservationResponse('http://example.com/', new \DateTime());

        $messages = [
            new ErrorMessage('error1', 1, '', 'ref1'),
            new WarningMessage('warning1', 2, '', 'ref1', 0),
            new InfoMessage('info1', 'description'),
            new ErrorMessage('error2', 3, '', 'ref1'),
            new WarningMessage('warning2', 4, '', 'ref2', 0),
            new ErrorMessage('error3', 5, '', 'ref2'),
        ];

        foreach ($messages as $message) {
            $observationResponse->addMessage($message);
        }

        $output = new ValidationOutput($options, $observationResponse);

        $this->assertEquals($messages, $output->getMessages());
        $this->assertEquals(
            [
                new ErrorMessage('error1', 1, '', 'ref1'),
                new ErrorMessage('error2', 3, '', 'ref1'),
                new ErrorMessage('error3', 5, '', 'ref2'),
            ],
            $output->getErrors()
        );
        $this->assertEquals(
            [
                new WarningMessage('warning1', 2, '', 'ref1', 0),
                new WarningMessage('warning2', 4, '', 'ref2', 0),
            ],
            $output->getWarnings()
        );
        $this->assertEquals(
            [
                new ErrorMessage('error1', 1, '', 'ref1'),
                new ErrorMessage('error2', 3, '', 'ref1'),
            ],
            $output->getErrorsByRef('ref1')
        );

        $this->assertEquals(3, $output->getErrorCount());
        $this->assertEquals(2, $output->getWarningCount());
        $this->assertEquals(1, $output->getInfoCount());
        $this->assertEquals(6, $output->getMessageCount());
    }
}
