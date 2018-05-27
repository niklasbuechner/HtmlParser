<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tests\TestResources\TestDomBuilderFactory;
use HtmlParser\Tests\TestResources\TestTreeConstructionTokenizer;
use HtmlParser\Tokenizer\HtmlTokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
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

    public function testRawTextElement()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $inHeadInsertionMode = new InHeadInsertionMode();

        $treeConstructor->setTokenizer(new TestTreeConstructionTokenizer());

        $titleTagToken = new StartTagToken();
        $titleTagToken->appendCharacterToName('style');

        $inHeadInsertionMode->processToken($titleTagToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('style', $domBuilder->getCurrentNode()->getName());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\TextInsertionMode', $treeConstructor->getInsertionMode());
        $this->assertTrue($treeConstructor->getTokenizer()->isInRawTextState());
    }

    public function testScriptTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $inHeadInsertionMode = new InHeadInsertionMode();

        $treeConstructor->setTokenizer(new TestTreeConstructionTokenizer());

        $titleTagToken = new StartTagToken();
        $titleTagToken->appendCharacterToName('script');

        $inHeadInsertionMode->processToken($titleTagToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('script', $domBuilder->getCurrentNode()->getName());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\TextInsertionMode', $treeConstructor->getInsertionMode());
        $this->assertTrue($treeConstructor->getTokenizer()->isInScriptDataState());
    }

    public function testHeadEndTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $inHeadInsertionMode = new InHeadInsertionMode();

        $headEndTag = new EndTagToken();
        $headEndTag->appendCharacterToName('head');

        $inHeadInsertionMode->processToken($headEndTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('html', $domBuilder->getCurrentNode()->getName());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\AfterHeadInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testAcceptableEndTags()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $inHeadInsertionMode = new InHeadInsertionMode();

        $treeConstructor->setInsertionMode($inHeadInsertionMode);

        $headEndTag = new EndTagToken();
        $headEndTag->appendCharacterToName('body');

        $inHeadInsertionMode->processToken($headEndTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertFalse($treeConstructor->getInsertionMode() instanceof InHeadInsertionMode);
    }

    public function testWhitespace()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $elementFactory = new ElementFactory();
        $inHeadInsertionMode = new InHeadInsertionMode();

        $inHeadInsertionMode->processToken(
            new CharacterToken('a'),
            $treeConstructor,
            $elementFactory,
            $domBuilder
        );

        $this->assertInstanceOf(
            'HtmlParser\TreeConstructor\Nodes\TextNode',
            $domBuilder->getCurrentNode()->getLastChild()
        );
        $this->assertEquals('a', $domBuilder->getCurrentNode()->getLastChild()->getData());
    }
}
