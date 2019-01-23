<?php
/** @noinspection PhpUnusedParameterInspection */
/** @noinspection PhpDocSignatureInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\AbstractIssueMessage;
use webignition\CssValidatorOutput\Model\AbstractMessage;
use webignition\CssValidatorOutput\Model\ErrorMessage;
use webignition\CssValidatorOutput\Model\InfoMessage;
use webignition\CssValidatorOutput\Model\MessageList;
use webignition\CssValidatorOutput\Model\WarningMessage;

class MessageListTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MessageList
     */
    private $messageList;

    protected function setUp()
    {
        parent::setUp();

        $messages = [
            new ErrorMessage('error1', 1, '.error1', 'ref1'),
            new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
            new InfoMessage('info1', 'description'),
            new ErrorMessage('error2', 3, '.error2', 'ref1'),
            new WarningMessage('warning2', 4, '.warning2', 'ref2', 0),
            new ErrorMessage('error3', 5, '.error3', 'ref2'),
        ];

        $this->messageList = new MessageList();

        foreach ($messages as $message) {
            $this->messageList->addMessage($message);
        }
    }

    /**
     * @dataProvider addMessageDataProvider
     */
    public function testAddMessage(
        AbstractMessage $message,
        int $expectedErrorCount,
        int $expectedWarningCount,
        int $expectedInfoCount,
        int $expectedMessageCount
    ) {
        $this->messageList->addMessage($message);

        $this->assertEquals($expectedErrorCount, $this->messageList->getErrorCount());
        $this->assertEquals($expectedWarningCount, $this->messageList->getWarningCount());
        $this->assertEquals($expectedInfoCount, $this->messageList->getInfoCount());
        $this->assertEquals($expectedMessageCount, $this->messageList->getMessageCount());
    }

    public function addMessageDataProvider(): array
    {
        return [
            'add error' => [
                'message' => new ErrorMessage('error4', 1, '.error4', 'ref1'),
                'expectedErrorCount' => 4,
                'expectedWarningCount' => 2,
                'expectedInfoCount' => 1,
                'expectedMessageCount' => 7,
            ],
            'add warning' => [
                'message' => new WarningMessage('warning3', 1, '.warning3', 'ref1', 0),
                'expectedErrorCount' => 3,
                'expectedWarningCount' => 3,
                'expectedInfoCount' => 1,
                'expectedMessageCount' => 7,
            ],
            'add info' => [
                'message' => new InfoMessage('info2', 'description'),
                'expectedErrorCount' => 3,
                'expectedWarningCount' => 2,
                'expectedInfoCount' => 2,
                'expectedMessageCount' => 7,
            ],
        ];
    }

    public function testGetErrorsByRef()
    {
        $this->assertEquals(
            [
                new ErrorMessage('error1', 1, '.error1', 'ref1'),
                new ErrorMessage('error2', 3, '.error2', 'ref1'),
            ],
            $this->messageList->getErrorsByRef('ref1')
        );

        $this->assertEquals(
            [
                new ErrorMessage('error3', 5, '.error3', 'ref2'),
            ],
            $this->messageList->getErrorsByRef('ref2')
        );
    }

    public function testGetMessages()
    {
        $this->assertEquals(
            [
                new ErrorMessage('error1', 1, '.error1', 'ref1'),
                new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                new InfoMessage('info1', 'description'),
                new ErrorMessage('error2', 3, '.error2', 'ref1'),
                new WarningMessage('warning2', 4, '.warning2', 'ref2', 0),
                new ErrorMessage('error3', 5, '.error3', 'ref2'),
            ],
            array_values($this->messageList->getMessages())
        );
    }

    public function testGetErrors()
    {
        $this->assertEquals(
            [
                new ErrorMessage('error1', 1, '.error1', 'ref1'),
                new ErrorMessage('error2', 3, '.error2', 'ref1'),
                new ErrorMessage('error3', 5, '.error3', 'ref2'),
            ],
            $this->messageList->getErrors()
        );
    }

    public function testGetWarnings()
    {
        $this->assertEquals(
            [
                new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                new WarningMessage('warning2', 4, '.warning2', 'ref2', 0),
            ],
            $this->messageList->getWarnings()
        );
    }

    /**
     * @dataProvider mutateDataProvider
     */
    public function testMutate(MessageList $originalMessageList, callable $mutator, array $expectedMessages)
    {
        $mutatedMessageList = $originalMessageList->mutate($mutator);
        $this->assertNotSame($originalMessageList, $mutatedMessageList);

        $mutatedMessages = array_values($mutatedMessageList->getMessages());
        /** @noinspection PhpParamsInspection */
        $this->assertCount(count($expectedMessages), $mutatedMessages);

        foreach ($mutatedMessages as $index => $mutatedMessage) {
            $this->assertEquals($expectedMessages[$index], $mutatedMessage);
        }
    }

    public function mutateDataProvider(): array
    {
        return [
            'no messages, non-modifying mutator' => [
                'originalMessageList' => new MessageList(),
                'mutator' => function (AbstractMessage $message) {
                    return $message;
                },
                'expectedMessages' => [],
            ],
            'has messages, non-modifying mutator' => [
                'originalMessageList' => new MessageList([
                    new ErrorMessage('title', 0, 'context', 'ref'),
                ]),
                'mutator' => function (AbstractMessage $message) {
                    return $message;
                },
                'expectedMessages' => [
                    new ErrorMessage('title', 0, 'context', 'ref'),
                ],
            ],
            'has messages, non-matching modifying mutator' => [
                'originalMessageList' => new MessageList([
                    new ErrorMessage('title', 0, 'context', 'ref'),
                ]),
                'mutator' => function (AbstractMessage $message) {
                    if ($message->isWarning()) {
                        $message = $message->withTitle('updated title');
                    }

                    return $message;
                },
                'expectedMessages' => [
                    new ErrorMessage('title', 0, 'context', 'ref'),
                ],
            ],
            'has messages, matching modifying mutator' => [
                'originalMessageList' => new MessageList([
                    new ErrorMessage('title', 0, 'context', 'original-ref'),
                ]),
                'mutator' => function (AbstractMessage $message) {
                    /* @var ErrorMessage $message */
                    if ($message instanceof ErrorMessage) {
                        $message = $message->withRef('updated-ref');
                    }

                    return $message;
                },
                'expectedMessages' => [
                    new ErrorMessage('title', 0, 'context', 'updated-ref'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider filterDataProvider
     */
    public function testFilter(MessageList $originalMessageList, callable $matcher, array $expectedMessages)
    {
        $filteredMessageList = $originalMessageList->filter($matcher);
        $this->assertNotSame($originalMessageList, $filteredMessageList);

        $filteredMessages = array_values($filteredMessageList->getMessages());
        /** @noinspection PhpParamsInspection */
        $this->assertCount(count($expectedMessages), $filteredMessages);

        foreach ($filteredMessages as $index => $mutatedMessage) {
            $this->assertEquals($expectedMessages[$index], $mutatedMessage);
        }
    }

    public function filterDataProvider(): array
    {
        return [
            'no messages, non-filtering filter' => [
                'originalMessageList' => new MessageList(),
                'matcher' => function (AbstractMessage $message): bool {
                    return true;
                },
                'expectedMessages' => [],
            ],
            'has messages, non-filtering filter' => [
                'originalMessageList' => new MessageList([
                    new ErrorMessage('title', 0, 'context', 'ref'),
                ]),
                'matcher' => function (AbstractMessage $message): bool {
                    return true;
                },
                'expectedMessages' => [
                    new ErrorMessage('title', 0, 'context', 'ref'),
                ],
            ],
            'has messages, non-matching filtering filter' => [
                'originalMessageList' => new MessageList([
                    new ErrorMessage('title', 0, 'context', 'non-matching-ref'),
                ]),
                'matcher' => function (AbstractMessage $message): bool {
                    if (!$message instanceof AbstractIssueMessage) {
                        return true;
                    }

                    return 'matching-ref' !== $message->getRef();
                },
                'expectedMessages' => [
                    new ErrorMessage('title', 0, 'context', 'non-matching-ref'),
                ],
            ],
            'has messages, matching filtering filter' => [
                'originalMessageList' => new MessageList([
                    new ErrorMessage('title', 0, 'context', 'matching-ref'),
                    new ErrorMessage('title', 0, 'context', 'non-matching-ref'),
                ]),
                'matcher' => function (AbstractMessage $message): bool {
                    if (!$message instanceof AbstractIssueMessage) {
                        return true;
                    }

                    return 'matching-ref' !== $message->getRef();
                },
                'expectedMessages' => [
                    new ErrorMessage('title', 0, 'context', 'non-matching-ref'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(array $messages, array $expectedMessages)
    {
        $messageList = new MessageList($messages);

        $this->assertEquals(
            $expectedMessages,
            array_values($messageList->getMessages())
        );
    }

    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'messages' => [],
                'expectedMessages' => [],
            ],
            'non-message values' => [
                'messages' => [1, 'string', true],
                'expectedMessages' => [],
            ],
            'message values' => [
                'messages' => [
                    new ErrorMessage('error1', 1, '.error1', 'ref1'),
                    new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                    new InfoMessage('info1', 'description'),
                ],
                'expectedMessages' => [
                    new ErrorMessage('error1', 1, '.error1', 'ref1'),
                    new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                    new InfoMessage('info1', 'description'),
                ],
            ],
            'message and non-message values' => [
                'messages' => [
                    1,
                    new ErrorMessage('error1', 1, '.error1', 'ref1'),
                    'string',
                    new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                    false,
                    new InfoMessage('info1', 'description'),
                ],
                'expectedMessages' => [
                    new ErrorMessage('error1', 1, '.error1', 'ref1'),
                    new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                    new InfoMessage('info1', 'description'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider mergeDataProvider
     */
    public function testMerge(
        MessageList $originalMessages,
        MessageList $additionalMessages,
        MessageList $expectedMessages
    ) {
        $mergedMessages = $originalMessages->merge($additionalMessages);

        $this->assertNotSame($originalMessages, $mergedMessages);
        $this->assertEquals(
            array_values($expectedMessages->getMessages()),
            array_values($mergedMessages->getMessages())
        );
    }

    public function mergeDataProvider(): array
    {
        return [
            'empty' => [
                'originalMessages' => new MessageList(),
                'additionalMessages' => new MessageList(),
                'expectedMessages' => new MessageList(),
            ],
            'additional messages all contained in original messages' => [
                'originalMessages' => new MessageList([
                    new ErrorMessage('error1', 1, '.error1', 'ref1'),
                    new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                    new InfoMessage('info1', 'description'),
                ]),
                'additionalMessages' => new MessageList([
                    new ErrorMessage('error1', 1, '.error1', 'ref1'),
                    new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                    new InfoMessage('info1', 'description'),
                ]),
                'expectedMessages' => new MessageList([
                    new ErrorMessage('error1', 1, '.error1', 'ref1'),
                    new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                    new InfoMessage('info1', 'description'),
                ]),
            ],
            'new messages' => [
                'originalMessages' => new MessageList([
                    new ErrorMessage('error1', 1, '.error1', 'ref1'),
                ]),
                'additionalMessages' => new MessageList([
                    new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                    new InfoMessage('info1', 'description'),
                ]),
                'expectedMessages' => new MessageList([
                    new ErrorMessage('error1', 1, '.error1', 'ref1'),
                    new WarningMessage('warning1', 2, '.warning1', 'ref1', 0),
                    new InfoMessage('info1', 'description'),
                ]),
            ],
        ];
    }
}
