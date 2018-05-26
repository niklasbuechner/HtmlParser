<?php
namespace HtmlParser\Tests\TreeConstructor;

use HtmlParser\TreeConstructor\DomBuilder;
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

    public function preventDocumentNodeFromBeingPopedOfStackOfOpenElements()
    {
        $domBuilder = new DomBuilder();

        $domBuilder->popLastElementOfStackOfOpenElements();
        $domBuilder->popLastElementOfStackOfOpenElements();
        $domBuilder->popLastElementOfStackOfOpenElements();

        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\DocumentNode', $domBuilder->getCurrentNode());
    }
}
