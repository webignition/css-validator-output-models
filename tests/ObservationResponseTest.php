<?php
/** @noinspection PhpDocMissingThrowsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\ErrorMessage;
use webignition\CssValidatorOutput\Model\InfoMessage;
use webignition\CssValidatorOutput\Model\ObservationResponse;
use webignition\CssValidatorOutput\Model\WarningMessage;

class ObservationResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $ref = 'http://example.com/';
        $dateTime = new \DateTime();

        $observationResponse = new ObservationResponse($ref, $dateTime);

        $this->assertEquals($ref, $observationResponse->getRef());
        $this->assertSame($dateTime, $observationResponse->getDateTime());
    }

    /**
     * @dataProvider addMessageDataProvider
     */
    public function testAddMessage(
        array $messages,
        int $expectedErrorCount,
        int $expectedWarningCount,
        int $expectedInfoCount
    ) {
        $observationResponse = new ObservationResponse('http://example.com', new \DateTime());

        foreach ($messages as $message) {
            $observationResponse->addMessage($message);
        }

        $this->assertEquals($expectedErrorCount, $observationResponse->getErrorCount());
        $this->assertEquals($expectedWarningCount, $observationResponse->getWarningCount());
        $this->assertEquals($expectedInfoCount, $observationResponse->getInfoCount());
    }

    public function addMessageDataProvider(): array
    {
        return [
            'no messages' => [
                'messages' => [],
                'expectedErrorCount' => 0,
                'expectedWarningCount' => 0,
                'expectedInfoCount' => 0,
            ],
            'single error' => [
                'messages' => [
                    new ErrorMessage('title', 0, '', ''),
                ],
                'expectedErrorCount' => 1,
                'expectedWarningCount' => 0,
                'expectedInfoCount' => 0,
            ],
            'single warning' => [
                'messages' => [
                    new WarningMessage('title', 0, '', '', 0),
                ],
                'expectedErrorCount' => 0,
                'expectedWarningCount' => 1,
                'expectedInfoCount' => 0,
            ],
            'single info' => [
                'messages' => [
                    new InfoMessage('title', 'description'),
                ],
                'expectedErrorCount' => 0,
                'expectedWarningCount' => 0,
                'expectedInfoCount' => 1,
            ],
            'multiple' => [
                'messages' => [
                    new ErrorMessage('title', 0, '', ''),
                    new WarningMessage('title', 0, '', '', 0),
                    new InfoMessage('title', 'description'),
                    new ErrorMessage('title', 0, '', ''),
                    new WarningMessage('title', 0, '', '', 0),
                    new ErrorMessage('title', 0, '', ''),
                ],
                'expectedErrorCount' => 3,
                'expectedWarningCount' => 2,
                'expectedInfoCount' => 1,
            ],
        ];
    }

    /**
     * @dataProvider getErrorsByRefDataProvider
     */
    public function testGetErrorsByRef(array $messages, string $ref, array $expectedErrors)
    {
        $observationResponse = new ObservationResponse('http://example.com', new \DateTime());

        foreach ($messages as $message) {
            $observationResponse->addMessage($message);
        }

        $errorsByRef = $observationResponse->getErrorsByRef($ref);

        $this->assertEquals($expectedErrors, $errorsByRef);
    }

    public function getErrorsByRefDataProvider()
    {
        return [
            'no messages' => [
                'messages' => [],
                'ref' => 'http://example.com/foo.css',
                'expectedErrors' => [],
            ],
            'no errors' => [
                'messages' => [
                    new WarningMessage('title', 0, '', 'http://example.com/foo.css', 0),
                    new InfoMessage('title', 'description'),
                ],
                'ref' => 'http://example.com/foo.css',
                'expectedErrors' => [],
            ],
            'no matching errors' => [
                'messages' => [
                    new ErrorMessage('title', 0, '', 'http://example.com/bar.css'),
                    new WarningMessage('title', 0, '', 'http://example.com/foo.css', 0),
                    new InfoMessage('title', 'description'),
                ],
                'ref' => 'http://example.com/foo.css',
                'expectedErrors' => [],
            ],
            'matching errors' => [
                'messages' => [
                    new ErrorMessage('title', 1, 'foo', 'http://example.com/foo.css'),
                    new WarningMessage('title', 0, '', 'http://example.com/foo.css', 0),
                    new InfoMessage('title', 'description'),
                    new ErrorMessage('title', 2, 'bar', 'http://example.com/foo.css'),
                ],
                'ref' => 'http://example.com/foo.css',
                'expectedErrors' => [
                    new ErrorMessage('title', 1, 'foo', 'http://example.com/foo.css'),
                    new ErrorMessage('title', 2, 'bar', 'http://example.com/foo.css'),
                ],
            ],
        ];
    }
}
