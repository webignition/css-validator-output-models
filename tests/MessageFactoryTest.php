<?php
/** @noinspection PhpDocSignatureInspection */
/** @noinspection PhpParamsInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\ErrorMessage;
use webignition\CssValidatorOutput\Model\MessageFactory;
use webignition\CssValidatorOutput\Model\WarningMessage;
use webignition\ValidatorMessage\MessageInterface;

class MessageFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createFromInvalidDOMElementDataProvider
     */
    public function testCreateFromInvalidDOMElement(string $xml)
    {
        $element = $this->createMessageDomElement($xml);

        $this->assertNull(MessageFactory::createFromDOMElement($element));
    }

    public function createFromInvalidDOMElementDataProvider(): array
    {
        return [
            'missing context element' => [
                'xml' => FixtureLoader::load('error-message-missing-context.xml'),
            ],
            'missing title element' => [
                'xml' => FixtureLoader::load('error-message-missing-title.xml'),
            ],
        ];
    }

    public function testCreateErrorMessageFromDOMElement()
    {
        $error = MessageFactory::createFromDOMElement($this->createMessageDomElement(
            FixtureLoader::load('error-message.xml')
        ));

        if ($error instanceof ErrorMessage) {
            $this->assertEquals(MessageInterface::TYPE_ERROR, $error->getType());
            $this->assertEquals('Parse Error
            *display: inline;', $error->getMessage());
            $this->assertEquals('audio, canvas, video', $error->getContext());
            $this->assertEquals(28, $error->getLineNumber());
            $this->assertTrue($error->isError());
            $this->assertEquals('http://example.com/css/bootstrap.css', $error->getRef());
        } else {
            $this->fail('$error is not an instance of ErrorMessage');
        }
    }

    public function testCreateWarningMessageFromDOMElement()
    {
        /* @var WarningMessage $warning */
        $warning = MessageFactory::createFromDOMElement($this->createMessageDomElement(
            FixtureLoader::load('warning-message.xml')
        ));

        if ($warning instanceof WarningMessage) {
            $this->assertEquals(MessageInterface::TYPE_WARNING, $warning->getType());
            $this->assertEquals(
                "You should add a 'type' attribute with a value of 'text/css' to the 'link' element",
                $warning->getMessage()
            );
            $this->assertEquals('', $warning->getContext());
            $this->assertEquals(5, $warning->getLineNumber());
            $this->assertTrue($warning->isWarning());
            $this->assertEquals(0, $warning->getLevel());
            $this->assertEquals('http://example.com/', $warning->getRef());
        } else {
            $this->fail('$warning is not an instance of WarningMessage');
        }
    }

    public function testCreateFailure()
    {
        $domElement = new \DOMElement('foo');

        $message = MessageFactory::createFromDOMElement($domElement);

        $this->assertNull($message);
    }

    public function testCreateWarningFromError()
    {
        $title = 'foo';
        $context = '.foo {}';
        $ref = 'http://example.com/foo.css';
        $lineNumber = 12;

        $error = new ErrorMessage($title, $lineNumber, $context, $ref);

        $warning = MessageFactory::createWarningFromError($error);

        $this->assertInstanceOf(WarningMessage::class, $warning);
        $this->assertEquals($title, $warning->getMessage());
        $this->assertEquals($context, $warning->getContext());
        $this->assertEquals($ref, $warning->getRef());
        $this->assertEquals($lineNumber, $warning->getLineNumber());
        $this->assertEquals(0, $warning->getLevel());
    }

    private function createMessageDomElement(string $xml): ?\DOMElement
    {
        $outputDom = new \DOMDocument();
        $outputDom->loadXML($xml);

        $element = $outputDom->getElementsByTagName('message')->item(0);

        return $element instanceof \DOMElement
            ? $element
            : null;
    }
}
