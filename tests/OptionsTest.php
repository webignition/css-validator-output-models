<?php

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\Options;

class OptionsTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $vendorExtensionIssuesAsWarnings = false;
        $outputFormat = 'ucn';
        $language = 'en';
        $warningLevel = 2;
        $medium = 'all';
        $profile = 'css3';

        $options = new Options(
            $vendorExtensionIssuesAsWarnings,
            $outputFormat,
            $language,
            $warningLevel,
            $medium,
            $profile
        );

        $this->assertEquals($vendorExtensionIssuesAsWarnings, $options->getVendorExtensionIssuesAsWarnings());
        $this->assertEquals($outputFormat, $options->getOutputFormat());
        $this->assertEquals($language, $options->getLanguage());
        $this->assertEquals($warningLevel, $options->getWarningLevel());
        $this->assertEquals($medium, $options->getMedium());
        $this->assertEquals($profile, $options->getProfile());
    }
}
