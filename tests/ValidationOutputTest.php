<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

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

        $observationResponse = new ObservationResponse('http://example.com/', new \DateTime());

        $output = new ValidationOutput($options, $observationResponse);

        $this->assertSame($options, $output->getOptions());
        $this->assertSame($observationResponse, $output->getObservationResponse());

        $this->assertTrue($output->isValidationOutput());
        $this->assertFalse($output->isIncorrectUsageOutput());
        $this->assertFalse($output->isExceptionOutput());
    }
}
