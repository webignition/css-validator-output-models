<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\ExceptionOutput;

class ExceptionOutputTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, ?int $code)
    {
        $output = new ExceptionOutput($type, $code);

        $this->assertEquals($type, $output->getType());
        $this->assertEquals($code, $output->getCode());

        $this->assertTrue($output->isExceptionOutput());
        $this->assertFalse($output->isIncorrectUsageOutput());
        $this->assertFalse($output->isValidationOutput());
    }

    public function createDataProvider(): array
    {
        return [
            'http 404' => [
                'type' => ExceptionOutput::TYPE_HTTP,
                'code' => 404,
            ],
            'curl 28' => [
                'type' => ExceptionOutput::TYPE_CURL,
                'code' => 28,
            ],
            'ssl error' => [
                'type' => ExceptionOutput::TYPE_SSL_ERROR,
                'code' => null,
            ],
            'unknown mime type' => [
                'type' => ExceptionOutput::TYPE_UNKNOWN_CONTENT_TYPE,
                'code' => null,
            ],
            'unknown host' => [
                'type' => ExceptionOutput::TYPE_UNKNOWN_HOST,
                'code' => null,
            ],
            'unknown file' => [
                'type' => ExceptionOutput::TYPE_UNKNOWN_FILE,
                'code' => null,
            ],
            'unknown' => [
                'type' => ExceptionOutput::TYPE_UNKNOWN,
                'code' => null,
            ],
        ];
    }
}
