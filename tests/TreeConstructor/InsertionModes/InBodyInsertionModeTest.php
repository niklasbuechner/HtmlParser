<?php
namespace HtmlParser\Tests\TreeConstructor\InsertionModes;

use HtmlParser\Tests\TestResources\TestDomBuilderFactory;
use HtmlParser\Tests\TestResources\TestTreeConstructionTokenizer;
use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\EndOfFileToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\InsertionModes\InBodyInsertionMode;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;
use PHPUnit\Framework\TestCase;

class InBodyInsertionModeTest extends TestCase
{
    public function testCharacterToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $characterToken = new CharacterToken('a');

        $inBodyInsertionMode->processToken($characterToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(1, $domBuilder->getCurrentNode()->getChildren());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\TextNode', $domBuilder->getCurrentNode()->getChildren()[0]);
        $this->assertFalse($domBuilder->getFramesetOkayFlag());
    }

    public function testCommentToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $commentToken = new CommentToken();

        $inBodyInsertionMode->processToken($commentToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(1, $domBuilder->getCurrentNode()->getChildren());
        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\CommentNode', $domBuilder->getCurrentNode()->getChildren()[0]);
    }

    public function testDoctypeToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $doctypeToken = new DoctypeToken();

        $inBodyInsertionMode->processToken($doctypeToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(0, $domBuilder->getCurrentNode()->getChildren());
    }

    public function testHtmlTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $htmlTag = new StartTagToken();
        $htmlTag->appendCharacterToName('html');
        $htmlTag->addAttribute(new AttributeStruct('background', 'red'));
        $htmlTag->addAttribute(new AttributeStruct('style', 'background: yellow;'));
        $htmlTag->prepareEmit();

        $inBodyInsertionMode->processToken($htmlTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(2, $domBuilder->getCurrentNode()->getAttributes());
    }

    public function testHtmlTagInsideOfTemplate()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('template'));

        $htmlTag = new StartTagToken();
        $htmlTag->appendCharacterToName('html');
        $htmlTag->addAttribute(new AttributeStruct('background', 'red'));
        $htmlTag->addAttribute(new AttributeStruct('style', 'background: yellow;'));
        $htmlTag->prepareEmit();

        $inBodyInsertionMode->processToken($htmlTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(0, $domBuilder->getCurrentNode()->getAttributes());
    }

    public function testTagBelongingToHead()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $linkTag = new StartTagToken();
        $linkTag->appendCharacterToName('link');

        $inBodyInsertionMode->processToken($linkTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(1, $domBuilder->getCurrentNode()->getChildren());
        $this->assertEquals('link', $domBuilder->getCurrentNode()->getChildren()[0]->getName());
    }

    public function testSecondBodyTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $bodyTag = new StartTagToken();
        $bodyTag->appendCharacterToName('body');
        $bodyTag->addAttribute(new AttributeStruct('style', 'color: red;'));
        $bodyTag->prepareEmit();

        $inBodyInsertionMode->processToken($bodyTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(1, $domBuilder->getStackOfOpenElements()[2]->getAttributes());
    }

    public function testFramesetTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $framesetToken = new StartTagToken();
        $framesetToken->appendCharacterToName('frameset');

        $inBodyInsertionMode->processToken($framesetToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('frameset', $domBuilder->getStackOfOpenElements()[2]->getName());
    }

    public function testIllegalFrameset()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->setFramesetOkayFlag(false);

        $framesetToken = new StartTagToken();
        $framesetToken->appendCharacterToName('frameset');

        $inBodyInsertionMode->processToken($framesetToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('body', $domBuilder->getStackOfOpenElements()[2]->getName());
    }

    public function testEndOfFileToken()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $eofToken = new EndOfFileToken();

        $inBodyInsertionMode->processToken($eofToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('body', $domBuilder->getCurrentNode()->getName());
    }

    public function testBodyTagEnd()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $bodyEndTag = new EndTagToken();
        $bodyEndTag->appendCharacterToName('body');

        $inBodyInsertionMode->processToken($bodyEndTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\AfterBodyInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testHtmlTagEnd()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $htmlEndTag = new EndTagToken();
        $htmlEndTag->appendCharacterToName('html');

        $inBodyInsertionMode->processToken($htmlEndTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertInstanceOf('HtmlParser\TreeConstructor\InsertionModes\AfterBodyInsertionMode', $treeConstructor->getInsertionMode());
    }

    public function testElementsBehavingLikeAddress()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));

        $navToken = new StartTagToken();
        $navToken->appendCharacterToName('nav');

        $inBodyInsertionMode->processToken($navToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('nav', $domBuilder->getCurrentNode()->getName());
    }

    public function testHeading()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));

        $headingToken = new StartTagToken();
        $headingToken->appendCharacterToName('h3');

        $inBodyInsertionMode->processToken($headingToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('h3', $domBuilder->getCurrentNode()->getName());
    }

    public function testPreTag()
    {
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $treeConstructor = new TreeConstructor($domBuilder);
        $treeConstructor->setInsertionMode(new InBodyInsertionMode());

        $preTag = new StartTagToken();
        $preTag->appendCharacterToName('pre');

        $treeConstructor->emitToken($preTag);
        $treeConstructor->emitToken(new CharacterToken('\n'));

        $this->assertEquals('pre', $domBuilder->getCurrentNode()->getName());
        $this->assertCount(0, $domBuilder->getCurrentNode()->getChildren());
        $this->assertFalse($domBuilder->getFramesetOkayFlag());
    }

    public function testFormTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $formToken = new StartTagToken();
        $formToken->appendCharacterToName('form');

        $inBodyInsertionMode->processToken($formToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertNotNull($domBuilder->getFormPointer());
        $this->assertEquals('form', $domBuilder->getCurrentNode()->getName());
    }

    public function testFormTagInTemplate()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('template'));

        $formToken = new StartTagToken();
        $formToken->appendCharacterToName('form');

        $inBodyInsertionMode->processToken($formToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertNull($domBuilder->getFormPointer());
        $this->assertEquals('form', $domBuilder->getCurrentNode()->getName());
    }

    public function testFormTagToBeIgnored()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('template'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('form'));
        $domBuilder->setFormPointerToCurrentNode();
        $domBuilder->popLastElementOfStackOfOpenElements();

        $formToken = new StartTagToken();
        $formToken->appendCharacterToName('form');

        $inBodyInsertionMode->processToken($formToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('template', $domBuilder->getCurrentNode()->getName());
    }

    public function testLiElementAndImplicitClosing()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('ul'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('li'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('b'));

        $liTag = new StartTagToken();
        $liTag->appendCharacterToName('li');

        $inBodyInsertionMode->processToken($liTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('li', $domBuilder->getCurrentNode()->getName());
        $this->assertCount(5, $domBuilder->getStackOfOpenElements());
        $this->assertFalse($domBuilder->getFramesetOkayFlag());
    }

    public function testLiElement()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('ul'));

        $liTag = new StartTagToken();
        $liTag->appendCharacterToName('li');

        $inBodyInsertionMode->processToken($liTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('li', $domBuilder->getCurrentNode()->getName());
        $this->assertCount(5, $domBuilder->getStackOfOpenElements());
        $this->assertFalse($domBuilder->getFramesetOkayFlag());
    }

    public function testDdElement()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $ddTag = new StartTagToken();
        $ddTag->appendCharacterToName('dd');

        $inBodyInsertionMode->processToken($ddTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('dd', $domBuilder->getCurrentNode()->getName());
        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
        $this->assertFalse($domBuilder->getFramesetOkayFlag());
    }

    public function testDtElement()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $dtTag = new StartTagToken();
        $dtTag->appendCharacterToName('dt');

        $inBodyInsertionMode->processToken($dtTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('dt', $domBuilder->getCurrentNode()->getName());
        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
        $this->assertFalse($domBuilder->getFramesetOkayFlag());
    }

    public function testPlaintext()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $treeConstructor->setTokenizer(new TestTreeConstructionTokenizer());

        $plainTextTag = new StartTagToken();
        $plainTextTag->appendCharacterToName('plaintext');

        $inBodyInsertionMode->processToken($plainTextTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('plaintext', $domBuilder->getCurrentNode()->getName());
        $this->assertTrue($treeConstructor->getTokenizer()->isInPlaintextState());
    }

    public function testButtonTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $buttonToken = new StartTagToken();
        $buttonToken->appendCharacterToName('button');

        $inBodyInsertionMode->processToken($buttonToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('button', $domBuilder->getCurrentNode()->getName());
        $this->assertFalse($domBuilder->getFramesetOkayFlag());
    }

    public function testSecondButtonTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('button'));

        $buttonToken = new StartTagToken();
        $buttonToken->appendCharacterToName('button');

        $inBodyInsertionMode->processToken($buttonToken, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertEquals('button', $domBuilder->getCurrentNode()->getName());
        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
        $this->assertFalse($domBuilder->getFramesetOkayFlag());
    }

    public function testGeneralEndTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('address'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('dd'));

        $addressEndTag = new EndTagToken();
        $addressEndTag->appendCharacterToName('address');

        $inBodyInsertionMode->processToken($addressEndTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(3, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('body', $domBuilder->getCurrentNode()->getName());
    }

    public function testFosterGeneralEndTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('dd'));

        $addressEndTag = new EndTagToken();
        $addressEndTag->appendCharacterToName('address');

        $inBodyInsertionMode->processToken($addressEndTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(5, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('dd', $domBuilder->getCurrentNode()->getName());
    }

    public function testFormEndTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('form'));
        $domBuilder->setFormPointerToCurrentNode();
        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('b'));
        $domBuilder->pushElementOntoListOfActiveFormattingElements($domBuilder->getCurrentNode());

        $endFormTag = new EndTagToken();
        $endFormTag->appendCharacterToName('form');

        $inBodyInsertionMode->processToken($endFormTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertNull($domBuilder->getFormPointer());
        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('b', $domBuilder->getCurrentNode()->getName());
    }

    public function testFormEndTagInTemplate()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('template'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('form'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('b'));
        $domBuilder->pushElementOntoListOfActiveFormattingElements($domBuilder->getCurrentNode());

        $endFormTag = new EndTagToken();
        $endFormTag->appendCharacterToName('form');

        $inBodyInsertionMode->processToken($endFormTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(5, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('b', $domBuilder->getCurrentNode()->getName());
    }

    public function testPEndTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));

        $pTag = new EndTagToken();
        $pTag->appendCharacterToName('p');

        $inBodyInsertionMode->processToken($pTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(3, $domBuilder->getStackOfOpenElements());
    }

    public function testFosterPEndTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $pTag = new EndTagToken();
        $pTag->appendCharacterToName('p');

        $inBodyInsertionMode->processToken($pTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(3, $domBuilder->getStackOfOpenElements());
        $this->assertCount(1, $domBuilder->getCurrentNode()->getChildren());
    }

    public function testLiEndTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('ul'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('li'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('hello'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));

        $liTag = new EndTagToken();
        $liTag->appendCharacterToName('li');

        $inBodyInsertionMode->processToken($liTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
    }

    public function testDtEndTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('ul'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('dt'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('hello'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));

        $dtTag = new EndTagToken();
        $dtTag->appendCharacterToName('dt');

        $inBodyInsertionMode->processToken($dtTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
    }

    public function testHeadingEndTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('ul'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('h1'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('hello'));
        $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));

        $h1Tag = new EndTagToken();
        $h1Tag->appendCharacterToName('h1');

        $inBodyInsertionMode->processToken($h1Tag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
    }

    public function testLinkTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $aTag = new StartTagToken();
        $aTag->appendCharacterToName('a');

        $inBodyInsertionMode->processToken($aTag, $treeConstructor, $elementFactory, $domBuilder);

        $this->assertCount(4, $domBuilder->getStackOfOpenElements());
        $this->assertEquals('a', $domBuilder->getCurrentNode()->getName());
    }

    public function testNestedLinkTag()
    {
        $treeConstructor = new TreeConstructor();
        $domBuilder = TestDomBuilderFactory::getDomBuilderWithBodyElement();
        $elementFactory = new ElementFactory();
        $inBodyInsertionMode = new InBodyInsertionMode();

        $domBuilder->insertNode($elementFactory->createElementFromTagName('a'));
        $domBuilder->pushElementOntoListOfActiveFormattingElements($domBuilder->getCurrentNode());

        $aTag = new StartTagToken();
        $aTag->appendCharacterToName('a');

        $inBodyInsertionMode->processToken($aTag, $treeConstructor, $elementFactory, $domBuilder);

        // $this->assertCount(4, $domBuilder->getStackOfOpenElements());
        // $this->assertEquals('a', $domBuilder->getCurrentNode()->getName());
    }
}
