<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
// use HtmlParser\TreeConstructor\InsertionModes\BeforeHtmlInsertionMode;
use HtmlParser\TreeConstructor\TreeConstructor;
use PHPUnit\Framework\TestCase;

class BeforeHtmlInsertionModeTest extends TestCase
{
    // public function testCommentToken()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

    //     $commentToken = new CommentToken();
    //     $commentToken->appendCharacterToData('hi');

    //     $beforeHtmlInsertionMode->processToken($commentToken, $treeConstructor);
    //     $nodes = $treeConstructor->getDocumentNode()->getChildren();

    //     $this->assertCount(1, $nodes);
    //     $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\CommentNode', $nodes[0]);
    //     $this->assertEquals('hi', $nodes[0]->getData());
    // }

    // public function testDoctypeToken()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

    //     $beforeHtmlInsertionMode->processToken(new DoctypeToken(), $treeConstructor);
    //     $nodes = $treeConstructor->getDocumentNode()->getChildren();

    //     $this->assertCount(0, $nodes);
    // }

    // public function testWhitespace()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

    //     $beforeHtmlInsertionMode->processToken(new CharacterToken(' '), $treeConstructor);
    //     $nodes = $treeConstructor->getDocumentNode()->getChildren();

    //     $this->assertCount(0, $nodes);
    // }

    // public function testHtmlStartTag()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

    //     $htmlToken = new StartTagToken();
    //     $htmlToken->appendCharacterToName('html');

    //     $beforeHtmlInsertionMode->processToken($htmlToken, $treeConstructor);
    //     $nodes = $treeConstructor->getDocumentNode()->getChildren();

    //     $this->assertCount(1, $nodes);
    //     $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\ElementNode', $nodes[0]);
    //     $this->assertCount(1, $treeConstructor->getStackOfOpenElements());
    //     $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\BeforeHeadInsertionMode', $treeConstructor->getInsertionMode());
    // }

    // public function testEndTag()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $beforeHtmlInsertionMode = new BeforeHtmlInsertionMode();

    //     $endTagToken = new EndTagToken();
    //     $endTagToken->appendCharacterToName('p');

    //     $beforeHtmlInsertionMode->processToken($endTagToken, $treeConstructor);
    //     $nodes = $treeConstructor->getDocumentNode()->getChildren();

    //     $this->assertCount(0, $nodes);
    //     $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\InitialInsertionMode', $treeConstructor->getInsertionMode());
    // }

    // public function testAcceptableEndTag()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $treeConstructor->setInsertionMode(new BeforeHtmlInsertionMode());

    //     $endTagToken = new EndTagToken();
    //     $endTagToken->appendCharacterToName('head');

    //     $treeConstructor->getInsertionMode()->processToken($endTagToken, $treeConstructor);

    //     $this->assertFalse($treeConstructor->getInsertionMode() instanceof BeforeHtmlInsertionMode);
    // }

    // public function testOtherTag()
    // {
    //     $treeConstructor = new TreeConstructor();
    //     $treeConstructor->setInsertionMode(new BeforeHtmlInsertionMode());

    //     $otherToken = new StartTagToken();
    //     $otherToken->appendCharacterToName('p');

    //     $treeConstructor->getInsertionMode()->processToken($otherToken, $treeConstructor);

    //     $this->assertFalse($treeConstructor->getInsertionMode() instanceof BeforeHtmlInsertionMode);
    // }
}
