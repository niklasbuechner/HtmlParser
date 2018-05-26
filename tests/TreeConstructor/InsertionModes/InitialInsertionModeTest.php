<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\TreeConstructor\InsertionModes\InitialInsertionMode;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;
use PHPUnit\Framework\TestCase;

class InitialInsertionModeTest extends TestCase
{
    public function testWhitespace()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $initialInsertionMode = new InitialInsertionMode();

        $initialInsertionMode->processToken(
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
        $initialInsertionMode = new InitialInsertionMode();

        $commentToken = new CommentToken();
        $commentToken->appendCharacterToData('hi');

        $initialInsertionMode->processToken($commentToken, $treeConstructor, $elementFactory, $domBuilder);
        $nodes = $domBuilder->getCurrentNode()->getChildren();

        $this->assertCount(1, $nodes);
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\CommentNode', $nodes[0]);
        $this->assertEquals('hi', $nodes[0]->getData());
    }

    public function testValidDoctypeToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $initialInsertionMode = new InitialInsertionMode();

        $doctypeToken = new DoctypeToken();
        $doctypeToken->appendCharacterToName('html');

        $initialInsertionMode->processToken(
            $doctypeToken,
            $treeConstructor,
            $elementFactory,
            $domBuilder
        );
        $doctypeNode = $domBuilder->getDocumentNode()->getDoctypeAttribute();

        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\DoctypeNode', $doctypeNode);
        $this->assertEquals('html', $doctypeNode->getName());
        $this->assertEquals('', $doctypeNode->getPublicIdentifier());
        $this->assertEquals('', $doctypeNode->getSystemIdentifier());
        $this->assertFalse($doctypeNode->getQuirksMode());

        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\BeforeHtmlInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testOtherToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = new DomBuilder();
        $elementFactory = new ElementFactory();
        $treeConstructor->setInsertionMode(new InitialInsertionMode());

        $treeConstructor->getInsertionMode()->processToken(
            new CharacterToken('a'),
            $treeConstructor,
            $elementFactory,
            $domBuilder
        );

        $this->assertFalse($treeConstructor->getInsertionMode() instanceof InitialInsertionMode);
    }
}
