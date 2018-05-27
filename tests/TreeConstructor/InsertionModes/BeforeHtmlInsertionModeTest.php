<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\InsertionModes\BeforeHtmlInsertionMode;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;
use PHPUnit\Framework\TestCase;

class BeforeHtmlInsertionModeTest extends TestCase
{
    public function testCommentToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

        $commentToken = new CommentToken();
        $commentToken->appendCharacterToData('hi');

        $beforeHtmlInsertionMode->processToken($commentToken, $treeConstructor, $elementFactory, $domBuilder);
        $nodes = $domBuilder->getCurrentNode()->getChildren();

        $this->assertCount(1, $nodes);
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\CommentNode', $nodes[0]);
        $this->assertEquals('hi', $nodes[0]->getData());
    }

    public function testDoctypeToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

        $beforeHtmlInsertionMode->processToken(new DoctypeToken(), $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(0, $domBuilder->getCurrentNode()->getChildren());
        $this->assertNull($domBuilder->getDocumentNode()->getDoctypeAttribute());
    }

    public function testWhitespace()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

        $beforeHtmlInsertionMode->processToken(new CharacterToken(' '), $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(0, $domBuilder->getDocumentNode()->getChildren());
    }

    public function testHtmlStartTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

        $htmlToken = new StartTagToken();
        $htmlToken->appendCharacterToName('html');

        $beforeHtmlInsertionMode->processToken($htmlToken, $treeConstructor, $elementFactory, $domBuilder);
        $nodes = $domBuilder->getDocumentNode()->getChildren();

        $this->assertCount(1, $nodes);
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\ElementNode', $nodes[0]);
        $this->assertCount(2, $domBuilder->getStackOfOpenElements());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\BeforeHeadInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testEndTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

        $endTagToken = new EndTagToken();
        $endTagToken->appendCharacterToName('p');

        $beforeHtmlInsertionMode->processToken($endTagToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(0, $domBuilder->getCurrentNode()->getChildren());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\InitialInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testAcceptableEndTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

        $endTagToken = new EndTagToken();
        $endTagToken->appendCharacterToName('head');

        $treeConstructor->getInsertionMode()->processToken($endTagToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertFalse($treeConstructor->getInsertionMode() instanceof BeforeHtmlInsertionMode);
    }

    public function testOtherTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

        $otherToken = new StartTagToken();
        $otherToken->appendCharacterToName('p');

        $treeConstructor->getInsertionMode()->processToken($otherToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertFalse($treeConstructor->getInsertionMode() instanceof BeforeHtmlInsertionMode);
    }
}
