<?php

namespace webignition\CssValidatorOutput\Model;

interface MessageListViewerInterface
{
    /**
     * @return AbstractMessage[]
     */
    public function getMessages(): array;

    /**
     * @return ErrorMessage[]
     */
    public function getErrors(): array;

    /**
     * @param string $ref
     *
     * @return ErrorMessage[]
     */
    public function getErrorsByRef(string $ref): array;

    /**
     * @return WarningMessage[]
     */
    public function getWarnings(): array;

    public function getErrorCount(): int;
    public function getWarningCount(): int;
    public function getInfoCount(): int;
    public function getMessageCount(): int;
}
