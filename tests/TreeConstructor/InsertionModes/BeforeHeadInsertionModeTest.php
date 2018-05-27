<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tests\TestResources\TestDomBuilderFactory;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\InsertionModes\BeforeHeadInsertionMode;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;
use PHPUnit\Framework\TestCase;

class BeforeHeadInsertionModeTest extends TestCase
{
    public function testComment()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHtmlElement();
        $elementFactory = new ElementFactory();
        $beforeHeadInsertionMode = new BeforeHeadInsertionMode();

        $commentToken = new CommentToken();
        $commentToken->appendCharacterToData('hi');

        $beforeHeadInsertionMode->processToken($commentToken, $treeConstructor, $elementFactory, $domBuilder);
        $nodes = $domBuilder->getCurrentNode()->getChildren();

        $this->assertCount(1, $nodes);
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\CommentNode', $nodes[0]);
    }

    public function testWhitespace()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHtmlElement();
        $elementFactory = new ElementFactory();
        $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());

        $treeConstructor->getInsertionMode()->processToken(
            new CharacterToken(' '),
            $treeConstructor,
            $elementFactory,
            $domBuilder
        );

        $this->assertCount(0, $domBuilder->getCurrentNode()->getChildren());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\BeforeHeadInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testOtherToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHtmlElement();
        $elementFactory = new ElementFactory();
        $beforeHeadInsertionMode = new BeforeHeadInsertionMode();

        $divToken = new StartTagToken();
        $divToken->appendCharacterToName('div');

        $beforeHeadInsertionMode->processToken(
            $divToken,
            $treeConstructor,
            $elementFactory,
            $domBuilder
        );
        $nodes = $domBuilder->getDocumentNode()->getChildren()[0]->getChildren();

        $this->assertFalse($treeConstructor->getInsertionMode() instanceof BeforeHeadInsertionMode);
        $this->assertCount(1, $nodes);
        $this->assertEquals('head', $nodes[0]->getName());
    }

    // public function testHtmlTag()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());
    //     $treeConstructor->insertNode($treeConstructor->createElementFromTagName('html'));

    //     $htmlToken = new StartTagToken();
    //     $htmlToken->appendCharacterToName('html');

    //     $treeConstructor->getInsertionMode()->processToken($htmlToken, $treeConstructor);

    //     // TODO
    // }

    public function testDoctypeToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHtmlElement();
        $elementFactory = new ElementFactory();
        $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());

        $treeConstructor->getInsertionMode()->processToken(
            new DoctypeToken(),
            $treeConstructor,
            $elementFactory,
            $domBuilder
        );

        $this->assertCount(0, $domBuilder->getCurrentNode()->getChildren());
        $this->assertEquals('html', $domBuilder->getCurrentNode()->getName());
        $this->assertNull($domBuilder->getDocumentNode()->getDoctypeAttribute());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\BeforeHeadInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testHeadTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHtmlElement();
        $elementFactory = new ElementFactory();
        $beforeHeadInsertionMode = new BeforeHeadInsertionMode();

        $divToken = new StartTagToken();
        $divToken->appendCharacterToName('head');

        $beforeHeadInsertionMode->processToken($divToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertFalse($treeConstructor->getInsertionMode() instanceof BeforeHeadInsertionMode);
        $this->assertCount(1, $domBuilder->getDocumentNode()->getChildren()[0]->getChildren());
        $this->assertEquals('head', $domBuilder->getCurrentNode()->getName());
    }

    public function testEndTagToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHtmlElement();
        $elementFactory = new ElementFactory();
        $beforeHeadInsertionMode = new BeforeHeadInsertionMode();

        $endTagToken = new EndTagToken();
        $endTagToken->appendCharacterToName('div');

        $beforeHeadInsertionMode->processToken($endTagToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('html', $domBuilder->getCurrentNode()->getName());
    }

    public function testAcceptableEndTagToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHtmlElement();
        $elementFactory = new ElementFactory();
        $beforeHeadInsertionMode = new BeforeHeadInsertionMode();

        $endTagToken = new EndTagToken();
        $endTagToken->appendCharacterToName('head');

        $beforeHeadInsertionMode->processToken($endTagToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('html', $domBuilder->getCurrentNode()->getName());
    }
}
