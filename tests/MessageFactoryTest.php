<?php
/** @noinspection PhpParamsInspection */

namespace webignition\CssValidatorOutput\Model\Tests;

use webignition\CssValidatorOutput\Model\AbstractMessage;
use webignition\CssValidatorOutput\Model\ErrorMessage;
use webignition\CssValidatorOutput\Model\MessageFactory;
use webignition\CssValidatorOutput\Model\WarningMessage;

class MessageFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateErrorMessageFromDOMElement()
    {
        /* @var ErrorMessage $error */
        $error = MessageFactory::createFromDOMElement($this->createMessageDomElement('error-message.xml'));

        $this->assertInstanceOf(ErrorMessage::class, $error);
        $this->assertEquals('Parse Error
            *display: inline;', $error->getTitle());
        $this->assertEquals('audio, canvas, video', $error->getContext());
        $this->assertEquals(28, $error->getLineNumber());
        $this->assertTrue($error->isError());
        $this->assertEquals('http://example.com/css/bootstrap.css', $error->getRef());

        $this->assertEquals(AbstractMessage::TYPE_ERROR, $error->getType());
    }

    public function testCreateWarningMessageFromDOMElement()
    {
        /* @var WarningMessage $warning */
        $warning = MessageFactory::createFromDOMElement($this->createMessageDomElement('warning-message.xml'));

        $this->assertInstanceOf(WarningMessage::class, $warning);
        $this->assertEquals(
            "You should add a 'type' attribute with a value of 'text/css' to the 'link' element",
            $warning->getTitle()
        );
        $this->assertEquals('', $warning->getContext());
        $this->assertEquals(5, $warning->getLineNumber());
        $this->assertTrue($warning->isWarning());
        $this->assertEquals(0, $warning->getLevel());
        $this->assertEquals('http://example.com/', $warning->getRef());

        $this->assertEquals(AbstractMessage::TYPE_WARNING, $warning->getType());
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
        $this->assertEquals($title, $warning->getTitle());
        $this->assertEquals($context, $warning->getContext());
        $this->assertEquals($ref, $warning->getRef());
        $this->assertEquals($lineNumber, $warning->getLineNumber());
        $this->assertEquals(0, $warning->getLevel());
    }

    private function createMessageDomElement(string $fixtureName): \DOMElement
    {
        $outputDom = new \DOMDocument();
        $outputDom->loadXML(FixtureLoader::load($fixtureName));

        return $outputDom->getElementsByTagName('message')->item(0);
    }
}
