<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\InsertionModes\BeforeHeadInsertionMode;
use HtmlParser\TreeConstructor\TreeConstructor;
use PHPUnit\Framework\TestCase;

class BeforeHeadInsertionModeTest extends TestCase
{
    public function testComment()
    {
        $treeConstructor = new TreeConstructor();
        $beforeHeadInsertionMode = new BeforeHeadInsertionMode();

        $commentToken = new CommentToken();
        $commentToken->appendCharacterToData('hi');

        $beforeHeadInsertionMode->processToken($commentToken, $treeConstructor);
        $nodes = $treeConstructor->getDocumentNode()->getChildren();

        $this->assertCount(1, $nodes);
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\CommentNode', $nodes[0]);
    }

    public function testWhitespace()
    {
        $treeConstructor = new TreeConstructor();
        $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());

        $treeConstructor->getInsertionMode()->processToken(new CharacterToken(' '), $treeConstructor);
        $nodes = $treeConstructor->getDocumentNode()->getChildren();

        $this->assertCount(0, $nodes);
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\BeforeHeadInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testOtherToken()
    {
        $treeConstructor = new TreeConstructor();
        $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());
        $treeConstructor->insertNode($treeConstructor->createElementFromTagName('html'));

        $divToken = new StartTagToken();
        $divToken->appendCharacterToName('div');

        $treeConstructor->getInsertionMode()->processToken($divToken, $treeConstructor);
        $nodes = $treeConstructor->getDocumentNode()->getChildren();

        $this->assertFalse($treeConstructor->getInsertionMode() instanceof BeforeHeadInsertionMode);
        $this->assertCount(1, $nodes[0]->getChildren());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\ElementNode', $treeConstructor->getHeadPointer());
        $this->assertEquals('head', $nodes[0]->getChildren()[0]->getName());
    }

    public function testHtmlTag()
    {
        $treeConstructor = new TreeConstructor();
        $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());
        $treeConstructor->insertNode($treeConstructor->createElementFromTagName('html'));

        $htmlToken = new StartTagToken();
        $htmlToken->appendCharacterToName('html');

        $treeConstructor->getInsertionMode()->processToken($htmlToken, $treeConstructor);

        // TODO
    }

    public function testDoctypeToken()
    {
        $treeConstructor = new TreeConstructor();
        $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());
        $treeConstructor->insertNode($treeConstructor->createElementFromTagName('html'));

        $treeConstructor->getInsertionMode()->processToken(new DoctypeToken(), $treeConstructor);
        $nodes = $treeConstructor->getDocumentNode()->getChildren()[0]->getChildren();

        $this->assertCount(0, $nodes);
        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\BeforeHeadInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testHeadTag()
    {
        $treeConstructor = new TreeConstructor();
        $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());
        $treeConstructor->insertNode($treeConstructor->createElementFromTagName('html'));

        $divToken = new StartTagToken();
        $divToken->appendCharacterToName('head');

        $treeConstructor->getInsertionMode()->processToken($divToken, $treeConstructor);
        $nodes = $treeConstructor->getDocumentNode()->getChildren();

        $this->assertFalse($treeConstructor->getInsertionMode() instanceof BeforeHeadInsertionMode);
        $this->assertCount(1, $nodes[0]->getChildren());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\ElementNode', $treeConstructor->getHeadPointer());
        $this->assertEquals('head', $nodes[0]->getChildren()[0]->getName());
    }

    public function testEndTagToken()
    {
        $treeConstructor = new TreeConstructor();
        $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());
        $treeConstructor->insertNode($treeConstructor->createElementFromTagName('html'));

        $endTagToken = new EndTagToken();
        $endTagToken->appendCharacterToName('div');

        $treeConstructor->getInsertionMode()->processToken($endTagToken, $treeConstructor);
        $nodes = $treeConstructor->getDocumentNode()->getChildren()[0]->getChildren();

        $this->assertCount(0, $nodes);
    }

    public function testAcceptableEndTagToken()
    {
        $treeConstructor = new TreeConstructor();
        $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());
        $treeConstructor->insertNode($treeConstructor->createElementFromTagName('html'));

        $endTagToken = new EndTagToken();
        $endTagToken->appendCharacterToName('head');

        $treeConstructor->getInsertionMode()->processToken($endTagToken, $treeConstructor);
        $nodes = $treeConstructor->getDocumentNode()->getChildren()[0]->getChildren();

        $this->assertCount(1, $nodes);
    }
}
