<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\ExceptionOutput;

class ExceptionOutputTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, string $subType, string $expectedStringRepresentation)
    {
        $output = new ExceptionOutput($type, $subType);

        $this->assertEquals($type, $output->getType());
        $this->assertEquals($subType, $output->getSubType());
        $this->assertEquals($expectedStringRepresentation, (string) $output);

        $this->assertTrue($output->isExceptionOutput());
        $this->assertFalse($output->isIncorrectUsageOutput());
        $this->assertFalse($output->isValidationOutput());
    }

    public function createDataProvider(): array
    {
        return [
            'http 404' => [
                'type' => ExceptionOutput::TYPE_HTTP,
                'subType' => '404',
                'expectedStringRepresentation' => 'http:404',
            ],
            'curl 28' => [
                'type' => ExceptionOutput::TYPE_CURL,
                'subType' => '28',
                'expectedStringRepresentation' => 'curl:28',
            ],
            'ssl error' => [
                'type' => ExceptionOutput::TYPE_SSL_ERROR,
                'subType' => '',
                'expectedStringRepresentation' => 'ssl-error',
            ],
            'unknown content type' => [
                'type' => ExceptionOutput::TYPE_UNKNOWN_CONTENT_TYPE,
                'subType' => 'application/pdf',
                'expectedStringRepresentation' => 'invalid-content-type:application/pdf',
            ],
            'unknown host' => [
                'type' => ExceptionOutput::TYPE_UNKNOWN_HOST,
                'subType' => '',
                'expectedStringRepresentation' => 'unknown-host',
            ],
            'unknown file' => [
                'type' => ExceptionOutput::TYPE_UNKNOWN_FILE,
                'subType' => '',
                'expectedStringRepresentation' => 'unknown-file',
            ],
            'unknown' => [
                'type' => ExceptionOutput::TYPE_UNKNOWN,
                'subType' => '',
                'expectedStringRepresentation' => 'unknown',
            ],
        ];
    }
}
