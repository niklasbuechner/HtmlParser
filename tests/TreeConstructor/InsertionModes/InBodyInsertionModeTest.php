<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tests\TestResources\TestDomBuilderFactory;
use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
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
}
