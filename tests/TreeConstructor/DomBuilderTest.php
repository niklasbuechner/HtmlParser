<?php
namespace HtmlParser\Tests\TreeConstructor;

use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\Nodes\CommentNode;
use HtmlParser\TreeConstructor\Nodes\ElementNode;
use PHPUnit\Framework\TestCase;

class DomBuilderTest extends TestCase
{
    public function testAddComment()
    {
        $domBuilder = new DomBuilder();

        $domBuilder->addComment(new CommentNode('Comment content'));

        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\DocumentNode', $domBuilder->getCurrentNode());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\CommentNode', $domBuilder->getCurrentNode()->getChildren()[0]);
        $this->assertEquals('Comment content', $domBuilder->getCurrentNode()->getChildren()[0]->getData());
    }

    public function testInsertNode()
    {
        $domBuilder = new DomBuilder();
        $domBuilder->insertNode(new ElementNode('title'));

        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\ElementNode', $domBuilder->getCurrentNode());
        $this->assertEquals('title', $domBuilder->getCurrentNode()->getName());
    }

    public function testPopLastElementOfStackOfOpenElements()
    {
        $domBuilder = new DomBuilder();
        $domBuilder->insertNode(new ElementNode('p'));

        $domBuilder->popLastElementOfStackOfOpenElements();

        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\DocumentNode', $domBuilder->getCurrentNode());
    }

    public function testPreventDocumentNodeFromBeingPopedOfStackOfOpenElements()
    {
        $domBuilder = new DomBuilder();

        $domBuilder->popLastElementOfStackOfOpenElements();
        $domBuilder->popLastElementOfStackOfOpenElements();
        $domBuilder->popLastElementOfStackOfOpenElements();

        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\DocumentNode', $domBuilder->getCurrentNode());
    }

    public function testInsertCharacterIntoDocument()
    {
        $domBuilder = new DomBuilder();

        $domBuilder->insertCharacter('Hi');
    }

    public function testInsertCharacterIntoNewTextNode()
    {
        $domBuilder = new DomBuilder();
        $domBuilder->insertNode((new ElementFactory())->createElementFromTagName('div'));

        $domBuilder->insertCharacter('a');

        $nodes = $domBuilder->getCurrentNode()->getChildren();

        $this->assertCount(1, $nodes);
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\TextNode', $nodes[0]);
        $this->assertEquals('a', $nodes[0]->getData());
    }

    public function testInsertCharacterIntoExistingNode()
    {
        $domBuilder = new DomBuilder();
        $domBuilder->insertNode((new ElementFactory())->createElementFromTagName('div'));

        $domBuilder->insertCharacter('a');
        $domBuilder->insertCharacter('b');
        $domBuilder->insertCharacter('c');
        $domBuilder->insertCharacter('d');

        $nodes = $domBuilder->getCurrentNode()->getChildren();

        $this->assertCount(1, $nodes);
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\TextNode', $nodes[0]);
        $this->assertEquals('abcd', $nodes[0]->getData());
    }
}
