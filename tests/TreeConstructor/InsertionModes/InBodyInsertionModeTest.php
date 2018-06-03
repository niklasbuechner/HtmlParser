<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tests\TestResources\TestDomBuilderFactory;
use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\EndOfFileToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\InsertionModes\InBodyInsertionMode;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;
use PHPUnit\Framework\TestCase;

class InBodyInsertionModeTest extends TestCase
{
    public function testCharacterToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $characterToken = new CharacterToken('a');

        $inBodyInsertionMode->processToken($characterToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(1, $domBuilder->getCurrentNode()->getChildren());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\TextNode', $domBuilder->getCurrentNode()->getChildren()[0]);
        $this->assertFalse($domBuilder->getFramesetOkayFlag());
    }

    public function testCommentToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $commentToken = new CommentToken();

        $inBodyInsertionMode->processToken($commentToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(1, $domBuilder->getCurrentNode()->getChildren());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\CommentNode', $domBuilder->getCurrentNode()->getChildren()[0]);
    }

    public function testDoctypeToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $doctypeToken = new DoctypeToken();

        $inBodyInsertionMode->processToken($doctypeToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(0, $domBuilder->getCurrentNode()->getChildren());
    }

    public function testHtmlTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $htmlTag = new StartTagToken();
        $htmlTag->appendCharacterToName('html');
        $htmlTag->addAttribute(new AttributeStruct('background', 'red'));
        $htmlTag->addAttribute(new AttributeStruct('style', 'background: yellow;'));
        $htmlTag->prepareEmit();

        $inBodyInsertionMode->processToken($htmlTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(2, $domBuilder->getCurrentNode()->getAttributes());
    }

    public function testHtmlTagInsideOfTemplate()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('template'));

        $htmlTag = new StartTagToken();
        $htmlTag->appendCharacterToName('html');
        $htmlTag->addAttribute(new AttributeStruct('background', 'red'));
        $htmlTag->addAttribute(new AttributeStruct('style', 'background: yellow;'));
        $htmlTag->prepareEmit();

        $inBodyInsertionMode->processToken($htmlTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(0, $domBuilder->getCurrentNode()->getAttributes());
    }

    public function testTagBelongingToHead()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $linkTag = new StartTagToken();
        $linkTag->appendCharacterToName('link');

        $inBodyInsertionMode->processToken($linkTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(1, $domBuilder->getCurrentNode()->getChildren());
        $this->assertEquals('link', $domBuilder->getCurrentNode()->getChildren()[0]->getName());
    }

    public function testSecondBodyTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $bodyTag = new StartTagToken();
        $bodyTag->appendCharacterToName('body');
        $bodyTag->addAttribute(new AttributeStruct('style', 'color: red;'));
        $bodyTag->prepareEmit();

        $inBodyInsertionMode->processToken($bodyTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(1, $domBuilder->getStackOfOpenElements()[2]->getAttributes());
    }

    public function testFramesetTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $framesetToken = new StartTagToken();
        $framesetToken->appendCharacterToName('frameset');

        $inBodyInsertionMode->processToken($framesetToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('frameset', $domBuilder->getStackOfOpenElements()[2]->getName());
    }

    public function testIllegalFrameset()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->setFramesetOkayFlag(false);

        $framesetToken = new StartTagToken();
        $framesetToken->appendCharacterToName('frameset');

        $inBodyInsertionMode->processToken($framesetToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('body', $domBuilder->getStackOfOpenElements()[2]->getName());
    }

    public function testEndOfFileToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $eofToken = new EndOfFileToken();

        $inBodyInsertionMode->processToken($eofToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('body', $domBuilder->getCurrentNode()->getName());
    }

    public function testBodyTagEnd()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $bodyEndTag = new EndTagToken();
        $bodyEndTag->appendCharacterToName('body');

        $inBodyInsertionMode->processToken($bodyEndTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\AfterBodyInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testHtmlTagEnd()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $htmlEndTag = new EndTagToken();
        $htmlEndTag->appendCharacterToName('html');

        $inBodyInsertionMode->processToken($htmlEndTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\AfterBodyInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testElementsBehavingLikeAddress()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));

        $navToken = new StartTagToken();
        $navToken->appendCharacterToName('nav');

        $inBodyInsertionMode->processToken($navToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('nav', $domBuilder->getCurrentNode()->getName());
    }

    public function testHeading()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));

        $headingToken = new StartTagToken();
        $headingToken->appendCharacterToName('h3');

        $inBodyInsertionMode->processToken($headingToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('h3', $domBuilder->getCurrentNode()->getName());
    }

    public function testPreTag()
    {
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $treeConstructor = new TreeConstructor($domBuilder);
        $treeConstructor->setInsertionMode(new InBodyInsertionMode());

        $preTag = new StartTagToken();
        $preTag->appendCharacterToName('pre');

        $treeConstructor->emitToken($preTag);
        $treeConstructor->emitToken(new CharacterToken('\n'));

        $this->assertEquals('pre', $domBuilder->getCurrentNode()->getName());
        $this->assertCount(0, $domBuilder->getCurrentNode()->getChildren());
        $this->assertFalse($domBuilder->getFramesetOkayFlag());
    }

    public function testFormTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $formToken = new StartTagToken();
        $formToken->appendCharacterToName('form');

        $inBodyInsertionMode->processToken($formToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertNotNull($domBuilder->getFormPointer());
        $this->assertEquals('form', $domBuilder->getCurrentNode()->getName());
    }

    public function testFormTagInTemplate()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('template'));

        $formToken = new StartTagToken();
        $formToken->appendCharacterToName('form');

        $inBodyInsertionMode->processToken($formToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertNull($domBuilder->getFormPointer());
        $this->assertEquals('form', $domBuilder->getCurrentNode()->getName());
    }

    public function testFormTagToBeIgnored()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('template'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('form'));
        $domBuilder->setFormPointerToCurrentNode();
        $domBuilder->popLastElementOfStackOfOpenElements();

        $formToken = new StartTagToken();
        $formToken->appendCharacterToName('form');

        $inBodyInsertionMode->processToken($formToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('template', $domBuilder->getCurrentNode()->getName());
    }
}
