<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\HtmlTokenizer;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
// use HtmlParser\TreeConstructor\InsertionModes\AfterHeadInsertionMode;
// use HtmlParser\TreeConstructor\InsertionModes\InHeadInsertionMode;
use HtmlParser\TreeConstructor\TreeConstructor;
use PHPUnit\Framework\TestCase;

class InHeadInsertionModeTest extends TestCase
{
    // public function testMetaTag()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $inHeadInsertionMode = new InHeadInsertionMode();

    //     $metaTagToken = new StartTagToken();
    //     $metaTagToken->appendCharacterToName('meta');

    //     $inHeadInsertionMode->processToken($metaTagToken, $treeConstructor);

    //     $this->assertCount(0, $treeConstructor->getStackOfOpenElements());
    //     $this->assertCount(1, $treeConstructor->getDocumentNode()->getChildren());
    // }

    // public function testLinkTag()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $inHeadInsertionMode = new InHeadInsertionMode();

    //     $linkTagToken = new StartTagToken();
    //     $linkTagToken->appendCharacterToName('link');

    //     $inHeadInsertionMode->processToken($linkTagToken, $treeConstructor);

    //     $this->assertCount(0, $treeConstructor->getStackOfOpenElements());
    //     $this->assertCount(1, $treeConstructor->getDocumentNode()->getChildren());
    // }

    // public function testDoctypeTag()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $inHeadInsertionMode = new InHeadInsertionMode();

    //     $inHeadInsertionMode->processToken(new DoctypeToken(), $treeConstructor);

    //     $this->assertCount(0, $treeConstructor->getStackOfOpenElements());
    //     $this->assertCount(0, $treeConstructor->getDocumentNode()->getChildren());
    //     $this->assertFalse($treeConstructor->getInsertionMode() instanceof AfterHeadInsertionMode);
    // }

    // public function testAnyOtherToken()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $inHeadInsertionMode = new InHeadInsertionMode();

    //     $treeConstructor->insertNode($treeConstructor->createElementFromTagName('div'));
    //     $inHeadInsertionMode->processToken(new StartTagToken(), $treeConstructor);

    //     $this->assertCount(0, $treeConstructor->getStackOfOpenElements());
    //     $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\AfterHeadInsertionMode', $treeConstructor->getInsertionMode());
    // }

    // public function testComment()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $treeConstructor->insertNode($treeConstructor->createElementFromTagName('head'));
    //     $inHeadInsertionMode = new InHeadInsertionMode();

    //     $inHeadInsertionMode->processToken(new CommentToken(), $treeConstructor);
    //     $stackOfOpenElements = $treeConstructor->getStackOfOpenElements();
    //     $children = $stackOfOpenElements[count($stackOfOpenElements) - 1]->getChildren();

    //     $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\CommentNode', $children[count($children) - 1]);
    // }

    // public function testTitle()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $treeConstructor->setTokenizer(new HtmlTokenizer($treeConstructor));
    //     $inHeadInsertionMode = new InHeadInsertionMode();

    //     $titleTagToken = new StartTagToken();
    //     $titleTagToken->appendCharacterToName('title');

    //     $inHeadInsertionMode->processToken($titleTagToken, $treeConstructor);
    //     $stackOfOpenElements = $treeConstructor->getStackOfOpenElements();

    //     $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\ElementNode', $stackOfOpenElements[count($stackOfOpenElements) - 1]);
    //     $this->assertEquals('title', $stackOfOpenElements[count($stackOfOpenElements) - 1]->getName());
    //     $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\TextInsertionMode', $treeConstructor->getInsertionMode());
    // }
}
