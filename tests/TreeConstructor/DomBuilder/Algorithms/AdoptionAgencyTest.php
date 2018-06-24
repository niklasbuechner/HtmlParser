<?php
namespace HtmlParser\Tests\TreeConstructor\DomBuilder\Algorithms;

use HtmlParser\Tokenizer\Tokens\EndTagToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\DomBuilder\Algorithms\AdoptionAgencyAlgorithm;
use HtmlParser\TreeConstructor\DomBuilder\ListOfActiveFormattingElements;
use HtmlParser\TreeConstructor\DomBuilder\StackOfOpenElements;
use HtmlParser\TreeConstructor\ElementFactory;
use PHPUnit\Framework\TestCase;

class AdoptionAgencyTest extends TestCase
{
    public function testWronglyNestedATag()
    {
        $elementFactory = new ElementFactory();
        $stackOfOpenElements = new StackOfOpenElements();
        $listOfActiveFormattingElements = new ListOfActiveFormattingElements();

        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('html'));
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('body'));
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('a'));
        $listOfActiveFormattingElements->add($stackOfOpenElements->getCurrentNode());
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('b'));
        $listOfActiveFormattingElements->add($stackOfOpenElements->getCurrentNode());

        $aToken = new StartTagToken();
        $aToken->appendCharacterToName('a');

        $adoptionAgencyAlgorithm = new AdoptionAgencyAlgorithm();
        $adoptionAgencyAlgorithm->runAdoptionAgencyAlgorithm($aToken, $stackOfOpenElements, $listOfActiveFormattingElements);

        $this->assertEquals('b', $stackOfOpenElements->getCurrentNode()->getName());
    }

    public function testNothingToDo()
    {
        $elementFactory = new ElementFactory();
        $stackOfOpenElements = new StackOfOpenElements();
        $listOfActiveFormattingElements = new ListOfActiveFormattingElements();

        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('html'));
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('body'));
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('a'));

        $aToken = new StartTagToken();
        $aToken->appendCharacterToName('a');

        $adoptionAgencyAlgorithm = new AdoptionAgencyAlgorithm();
        $adoptionAgencyAlgorithm->runAdoptionAgencyAlgorithm($aToken, $stackOfOpenElements, $listOfActiveFormattingElements);

        $this->assertCount(3, $stackOfOpenElements->getElements());
    }

    public function testMisNestedTagWithoutSpecialTag()
    {
        $elementFactory = new ElementFactory();
        $stackOfOpenElements = new StackOfOpenElements();
        $listOfActiveFormattingElements = new ListOfActiveFormattingElements();

        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('html'));
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('body'));
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('p'));
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('b'));
        $listOfActiveFormattingElements->add($stackOfOpenElements->getCurrentNode());
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('i'));
        $listOfActiveFormattingElements->add($stackOfOpenElements->getCurrentNode());

        $bToken = new EndTagToken();
        $bToken->appendCharacterToName('b');

        $adoptionAgencyAlgorithm = new AdoptionAgencyAlgorithm();
        $result = $adoptionAgencyAlgorithm->runAdoptionAgencyAlgorithm($bToken, $stackOfOpenElements, $listOfActiveFormattingElements);

        $this->assertCount(6, $stackOfOpenElements->getElements());
        $this->assertEquals('i', $stackOfOpenElements->getCurrentNode()->getName());
        $this->assertEquals($result, AdoptionAgencyAlgorithm::TREAT_AS_ANY_OTHER_END_TAG);
    }

    public function testMisNestedBAndPTags()
    {
        $elementFactory = new ElementFactory();
        $stackOfOpenElements = new StackOfOpenElements();
        $listOfActiveFormattingElements = new ListOfActiveFormattingElements();

        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('html'));
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('body'));
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('b'));
        $listOfActiveFormattingElements->add($stackOfOpenElements->getCurrentNode());
        $stackOfOpenElements->insertNode($elementFactory->createElementFromTagName('p'));

        $bToken = new EndTagToken();
        $bToken->appendCharacterToName('b');

        $adoptionAgencyAlgorithm = new AdoptionAgencyAlgorithm();
        $adoptionAgencyAlgorithm->runAdoptionAgencyAlgorithm($bToken, $stackOfOpenElements, $listOfActiveFormattingElements);

        $this->assertCount(4, $stackOfOpenElements->getElements());
        $this->assertEquals('p', $stackOfOpenElements->getCurrentNode()->getName());
    }
}
