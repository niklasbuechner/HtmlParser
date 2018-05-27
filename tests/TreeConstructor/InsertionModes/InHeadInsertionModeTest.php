<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tests\TestResources\TestDomBuilderFactory;
use HtmlParser\Tests\TestResources\TestTreeConstructionTokenizer;
use HtmlParser\Tokenizer\HtmlTokenizer;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\InsertionModes\AfterHeadInsertionMode;
use HtmlParser\TreeConstructor\InsertionModes\InHeadInsertionMode;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;
use PHPUnit\Framework\TestCase;

class InHeadInsertionModeTest extends TestCase
{
    public function testMetaTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $inHeadInsertionMode = new InHeadInsertionMode();

        $metaTagToken = new StartTagToken();
        $metaTagToken->appendCharacterToName('meta');

        $inHeadInsertionMode->processToken($metaTagToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('head', $domBuilder->getCurrentNode()->getName());
        $this->assertCount(1, $domBuilder->getCurrentNode()->getChildren());
        $this->assertEquals('meta', $domBuilder->getCurrentNode()->getChildren()[0]->getName());
    }

    public function testLinkTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $inHeadInsertionMode = new InHeadInsertionMode();

        $linkTagToken = new StartTagToken();
        $linkTagToken->appendCharacterToName('link');

        $inHeadInsertionMode->processToken($linkTagToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('head', $domBuilder->getCurrentNode()->getName());
        $this->assertCount(1, $domBuilder->getCurrentNode()->getChildren());
        $this->assertEquals('link', $domBuilder->getCurrentNode()->getChildren()[0]->getName());
    }

    public function testDoctypeTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $inHeadInsertionMode = new InHeadInsertionMode();

        $inHeadInsertionMode->processToken(new DoctypeToken(), $treeConstructor, $elementFactory, $domBuilder);

        $this->assertFalse($treeConstructor->getInsertionMode() instanceof AfterHeadInsertionMode);
    }

    public function testAnyOtherToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $inHeadInsertionMode = new InHeadInsertionMode();

        $inHeadInsertionMode->processToken(new StartTagToken(), $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('html', $domBuilder->getCurrentNode()->getName());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\AfterHeadInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testComment()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $inHeadInsertionMode = new InHeadInsertionMode();

        $inHeadInsertionMode->processToken(new CommentToken(), $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('head', $domBuilder->getCurrentNode()->getName());
        $this->assertCount(1, $domBuilder->getCurrentNode()->getChildren());
        $this->assertInstanceOf(
            'HtmlParser\TreeConstructor\Nodes\CommentNode',
            $domBuilder->getCurrentNode()->getChildren()[0]
        );
    }

    public function testTitle()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $inHeadInsertionMode = new InHeadInsertionMode();

        $treeConstructor->setTokenizer(new TestTreeConstructionTokenizer());

        $titleTagToken = new StartTagToken();
        $titleTagToken->appendCharacterToName('title');

        $inHeadInsertionMode->processToken($titleTagToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('title', $domBuilder->getCurrentNode()->getName());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\TextInsertionMode', $treeConstructor->getInsertionMode());
        $this->assertTrue($treeConstructor->getTokenizer()->isInRcdataState());
    }
}
