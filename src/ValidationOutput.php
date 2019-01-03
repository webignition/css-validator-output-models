<?php

namespace webignition\CssValidatorOutput\Model;

class ValidationOutput implements OutputInterface, MessageListViewerInterface
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

    public function getMessages(): array
    {
        return $this->observationResponse->getMessages();
    }

    public function getErrors(): array
    {
        return $this->observationResponse->getErrors();
    }

    public function getErrorsByRef(string $ref): array
    {
        return $this->observationResponse->getErrorsByRef($ref);
    }

    public function getWarnings(): array
    {
        return $this->observationResponse->getWarnings();
    }

    public function getErrorCount(): int
    {
        return $this->observationResponse->getErrorCount();
    }

    public function getWarningCount(): int
    {
        return $this->observationResponse->getWarningCount();
    }

    public function getInfoCount(): int
    {
        return $this->observationResponse->getInfoCount();
    }

    public function getMessageCount(): int
    {
        return $this->observationResponse->getMessageCount();
    }
}
