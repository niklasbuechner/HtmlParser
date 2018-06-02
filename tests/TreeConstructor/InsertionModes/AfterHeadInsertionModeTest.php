<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tests\TestResources\TestDomBuilderFactory;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\InsertionModes\AfterHeadInsertionMode;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;
use PHPUnit\Framework\TestCase;

class AfterHeadInsertionModeTest extends TestCase
{
    public function testWhitespace()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $afterHeadInsertionMode = new AfterHeadInsertionMode();

        $afterHeadInsertionMode->processToken(
            new CharacterToken(' '),
            $treeConstructor,
            $elementFactory,
            $domBuilder
        );

        $this->assertCount(0, $domBuilder->getCurrentNode()->getChildren());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\DocumentNode', $domBuilder->getCurrentNode());
    }

    public function testComment()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $afterHeadInsertionMode = new AfterHeadInsertionMode();

        $commentToken = new CommentToken();
        $commentToken->appendCharacterToData('hi');

        $afterHeadInsertionMode->processToken($commentToken, $treeConstructor, $elementFactory, $domBuilder);
        $nodes = $domBuilder->getCurrentNode()->getChildren();

        $this->assertCount(1, $nodes);
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\CommentNode', $nodes[0]);
        $this->assertEquals('hi', $nodes[0]->getData());
    }

    public function testDoctypeTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $afterHeadInsertionMode = new AfterHeadInsertionMode();

        $afterHeadInsertionMode->processToken(new DoctypeToken(), $treeConstructor, $elementFactory, $domBuilder);

        $this->assertFalse($treeConstructor->getInsertionMode() instanceof AfterHeadInsertionMode);
    }

    public function testAnyOtherToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $afterHeadInsertionMode = new AfterHeadInsertionMode();

        $afterHeadInsertionMode->processToken(new StartTagToken(), $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('body', $domBuilder->getCurrentNode()->getName());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\InBodyInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testBodyTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHtmlElement();
        $elementFactory = new ElementFactory();
        $afterHeadInsertionMode = new AfterHeadInsertionMode();

        $bodyToken = new StartTagToken();
        $bodyToken->appendCharacterToName('body');

        $afterHeadInsertionMode->processToken($bodyToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('body', $domBuilder->getCurrentNode()->getName());
        $this->assertFalse($domBuilder->getFramesetOkayFlag());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\InBodyInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testFramesetTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHtmlElement();
        $elementFactory = new ElementFactory();
        $afterHeadInsertionMode = new AfterHeadInsertionMode();

        $framesetTag = new StartTagToken();
        $framesetTag->appendCharacterToName('frameset');

        $afterHeadInsertionMode->processToken($framesetTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('frameset', $domBuilder->getCurrentNode()->getName());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\InFramesetInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testTagOwnedByHead()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $afterHeadInsertionMode = new AfterHeadInsertionMode();

        $domBuilder->popLastElementOfStackOfOpenElements();

        $linkTag = new StartTagToken();
        $linkTag->appendCharacterToName('link');

        $afterHeadInsertionMode->processToken($linkTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(1, $domBuilder->getHeadPointer()->getChildren());
        $this->assertEquals('link', $domBuilder->getHeadPointer()->getChildren()[0]->getName());
    }
}
