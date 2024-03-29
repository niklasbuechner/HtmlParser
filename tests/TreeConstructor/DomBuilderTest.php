<?php
namespace HtmlParser\Tests\TreeConstructor;

use Exception;
use HtmlParser\Tests\TestResources\TestDomBuilderFactory;
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

        try {
            $domBuilder->popLastElementOfStackOfOpenElements();
            $this->assertEquals('Trying to pop the dom elemnt of the stack, may not succeed.', 'Succeded in poping the document node of the stack');
        } catch (Exception $ex) {
            $this->assertTrue(true);
        }

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

    public function testGenerateImpliedEndTagsThorougly()
    {
        $domBuilder = new DomBuilder();
        $domBuilder->insertNode((new ElementFactory())->createElementFromTagName('p'));
        $domBuilder->insertNode((new ElementFactory())->createElementFromTagName('caption'));
        $domBuilder->insertNode((new ElementFactory())->createElementFromTagName('tr'));
        $domBuilder->insertNode((new ElementFactory())->createElementFromTagName('li'));

        $domBuilder->generateImpliedEndTagsThoroughly();

        $this->assertCount(1, $domBuilder->getStackOfOpenElements());
    }

    public function testClearListOfActiveFormattingElementsToNextMarker()
    {
        $elementFactory = new ElementFactory();

        $domBuilder = new DomBuilder();
        $domBuilder->pushMarkerOntoListOfActiveFormattingElements();
        $domBuilder->pushElementOntoListOfActiveFormattingElements($elementFactory->createElementFromTagName('i'));
        $domBuilder->pushElementOntoListOfActiveFormattingElements($elementFactory->createElementFromTagName('b'));
        $domBuilder->pushElementOntoListOfActiveFormattingElements($elementFactory->createElementFromTagName('u'));

        $domBuilder->clearListOfActiveFormattingElementsToNextMarker();

        $this->assertCount(0, $domBuilder->getListOfActiveFormattingElements());
    }

    public function testProcessingAsHead()
    {
        $elementFactory = new ElementFactory();

        $domBuilder = TestDomBuilderFactory::getDomBuilderWithHeadElement();
        $domBuilder->popLastElementOfStackOfOpenElements();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('body'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('div'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('h1'));

        $domBuilder->pushHeadToStackOfOpenElements();

        $this->assertEquals($domBuilder->getCurrentNode(), $domBuilder->getHeadPointer());

        $domBuilder->insertNode($elementFactory->createElementFromTagName('link'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('meta'));

        $domBuilder->removeHeadFromStackOfOpenElements();

        $this->assertCount(5, $domBuilder->getStackOfOpenElements());
    }

    public function testGetParentNodeOf()
    {
        $elementFactory = new ElementFactory();

        $divNode = $elementFactory->createElementFromTagName('div');
        $pNode = $elementFactory->createElementFromTagName('p');
        $bNode = $elementFactory->createElementFromTagName('b');
        $iNode = $elementFactory->createElementFromTagName('i');

        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $domBuilder->insertNode($divNode);
        $domBuilder->insertNode($pNode);
        $domBuilder->insertNode($bNode);
        $domBuilder->insertNode($iNode);

        $this->assertEquals($bNode, $domBuilder->getParentNodeOf($iNode));
        $this->assertEquals($pNode, $domBuilder->getParentNodeOf($bNode));
        $this->assertEquals($divNode, $domBuilder->getParentNodeOf($pNode));

        try {
            $domBuilder->getParentNodeOf($elementFactory->createElementFromTagName('li'));
            $this->assertFalse(true);
        } catch (Exception $exception) {
            $this->assertTrue(true);
        }
    }

    public function testSpecialTag()
    {
        $domBuilder = new DomBuilder();

        $this->assertTrue($domBuilder->isSpecialTag('p'));
        $this->assertTrue($domBuilder->isSpecialTag('meta'));
        $this->assertTrue($domBuilder->isSpecialTag('p', ['meta']));

        $this->assertFalse($domBuilder->isSpecialTag('p', ['p']));
        $this->assertFalse($domBuilder->isSpecialTag('b'));
    }

    public function testStackOfOpenElementsContainsElementInScope()
    {
        $elementFactory = new ElementFactory();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('div'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('b'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('h1'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('i'));

        $this->assertTrue($domBuilder->stackOfOpenElementsContainsElementInScope('i', []));
        $this->assertTrue($domBuilder->stackOfOpenElementsContainsElementInScope('p', ['div', 'body']));

        $this->assertFalse($domBuilder->stackOfOpenElementsContainsElementInScope('p', ['div', 'body', 'h1']));
        $this->assertFalse($domBuilder->stackOfOpenElementsContainsElementInScope('b', ['h1']));
    }

    public function testReconstructActiveFormattingList()
    {
        $elementFactory = new ElementFactory();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('b'));
        $domBuilder->pushElementOntoListOfActiveFormattingElements($elementFactory->createElementFromTagName('i'));
        $domBuilder->pushElementOntoListOfActiveFormattingElements($elementFactory->createElementFromTagName('u'));
        $domBuilder->pushElementOntoListOfActiveFormattingElements($elementFactory->createElementFromTagName('strong'));
        $domBuilder->pushElementOntoListOfActiveFormattingElements($elementFactory->createElementFromTagName('hi'));

        $domBuilder->reconstructActiveFormattingList();

        $this->assertCount(8, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('hi', $domBuilder->getStackOfOpenElements()[7]->getName());
        $this->assertEquals('strong', $domBuilder->getStackOfOpenElements()[6]->getName());
        $this->assertEquals('u', $domBuilder->getStackOfOpenElements()[5]->getName());
        $this->assertEquals('i', $domBuilder->getStackOfOpenElements()[4]->getName());
        $this->assertEquals('b', $domBuilder->getStackOfOpenElements()[3]->getName());
    }

    public function testStackofOpenElementsContainsElement()
    {
        $elementFactory = new ElementFactory();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();

        $h1Element = $elementFactory->createElementFromTagName('h1');
        $domBuilder->insertNode($h1Element);

        $this->assertTrue($domBuilder->stackOfOpenElementsContainsElement($h1Element));
        $this->assertFalse($domBuilder->stackOfOpenElementsContainsElement($elementFactory->createElementFromTagName('p')));
    }

    public function testStackofOpenElementsContainsElementWithTagName()
    {
        $elementFactory = new ElementFactory();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('h1'));

        $this->assertTrue($domBuilder->containsStackOfOpenElements('h1'));
        $this->assertFalse($domBuilder->containsStackOfOpenElements('p'));
    }

    public function testRemoveElementsOfStackOfOpenElementsUntilElementWithNameWasFound()
    {
        $elementFactory = new ElementFactory();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('b'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('u'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('i'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('strong'));

        $domBuilder->popElementsOfStackOfOpenElementsUntilElementWithName('p');

        $this->assertCount(3, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('body', $domBuilder->getCurrentNode()->getName());
    }

    public function testRemoveElementsOfStackOfOpenElementsUntilElementWasFound()
    {
        $elementFactory = new ElementFactory();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();

        $formElement = $elementFactory->createElementFromTagName('form');
        $domBuilder->insertNode($formElement);
        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('b'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('u'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('i'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('strong'));

        $domBuilder->popElementsOfStackOfOpenElementsUntilElement($formElement);

        $this->assertCount(3, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('body', $domBuilder->getCurrentNode()->getName());
    }
}
