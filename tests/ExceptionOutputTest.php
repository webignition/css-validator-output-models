<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\ExceptionOutput;

class ExceptionOutputTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, int $code)
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
        ];
    }
}
