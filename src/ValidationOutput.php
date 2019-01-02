<?php

namespace webignition\CssValidatorOutput\Model;

class ValidationOutput implements OutputInterface
{
    private $options;
    private $observationResponse;

    public function __construct(Options $options, ObservationResponse $observationResponse)
    {
        $this->options = $options;
        $this->observationResponse = $observationResponse;
    }

    public function getOptions(): Options
    {
        return $this->options;
    }

    public function getObservationResponse(): ObservationResponse
    {
        return $this->observationResponse;
    }

    public function isIncorrectUsageOutput(): bool
    {
        return false;
    }

    public function isExceptionOutput(): bool
    {
        return false;
    }

    public function isValidationOutput(): bool
    {
        return true;
    }
}
