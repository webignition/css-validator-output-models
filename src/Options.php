<?php

namespace webignition\CssValidatorOutput\Model;

class Options
{
    private $vendorExtensionIssuesAsWarnings;
    private $outputFormat;
    private $language;
    private $warningLevel;
    private $medium;
    private $profile;

    public function __construct(
        bool $vendorExtensionIssuesAsWarnings,
        string $outputFormat,
        string $language,
        int $warningLevel,
        string $medium,
        string $profile
    ) {
        $this->vendorExtensionIssuesAsWarnings = $vendorExtensionIssuesAsWarnings;
        $this->outputFormat = $outputFormat;
        $this->language = $language;
        $this->warningLevel = $warningLevel;
        $this->medium = $medium;
        $this->profile = $profile;
    }

    public function getVendorExtensionIssuesAsWarnings(): bool
    {
        return $this->vendorExtensionIssuesAsWarnings;
    }

    public function getOutputFormat(): string
    {
        return $this->outputFormat;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getWarningLevel(): int
    {
        return $this->warningLevel;
    }

    public function getMedium(): string
    {
        return $this->medium;
    }

    public function getProfile(): string
    {
        return $this->profile;
    }
}
