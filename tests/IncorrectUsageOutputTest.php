<?php

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\IncorrectUsageOutput;

class IncorrectUsageOutputTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $output = new IncorrectUsageOutput();

        $this->assertTrue($output->isIncorrectUsageOutput());
        $this->assertFalse($output->isExceptionOutput());
        $this->assertFalse($output->isValidationOutput());
    }
}
