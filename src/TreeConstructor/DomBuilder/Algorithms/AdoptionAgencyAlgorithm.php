<?php
namespace HtmlParser\TreeConstructor\DomBuilder\Algorithms;

use HtmlParser\Tokenizer\Tokens\Token;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\DomBuilder\ListOfActiveFormattingElements;
use HtmlParser\TreeConstructor\DomBuilder\StackOfOpenElements;
use HtmlParser\TreeConstructor\Nodes\ElementNode;

class AdoptionAgencyAlgorithm
{
    /**
     * Defines that the end tag needs to be treated as any other end tag.
     */
    const TREAT_AS_ANY_OTHER_END_TAG = 2;

    /**
     * Runs the adoption agency algorithm.
     *
     * @param Token $token
     * @param ListOfActiveFormattingElements $listOfActiveFormattingElements
     * @param StackOfOpenElements $stackOfOpenElements
     */
    public function runAdoptionAgencyAlgorithm(
        Token $token,
        StackOfOpenElements $stackOfOpenElements,
        ListOfActiveFormattingElements $listOfActiveFormattingElements
    ) {
        $subject = $token->getName();

        if ($subject === $stackOfOpenElements->getCurrentNode()->getName() && !$listOfActiveFormattingElements->containsBeforeMarker($stackOfOpenElements->getCurrentNode())) {
            $stackOfOpenElements->pop();

            return 0;
        }

        $outerLoopCounter = 0;
        while ($outerLoopCounter < 8) {
            $outerLoopCounter += 1;

            $formattingElement = $listOfActiveFormattingElements->getLastElementWithTagNameBeforeMarker($subject);

            if (!$formattingElement) {
                return 0;
            }

            if (!$stackOfOpenElements->contains($formattingElement)) {
                return 0;
            }

            $furthestBlockIndex = $this->getFurthestBlockIndex($formattingElement, $stackOfOpenElements);
            if ($furthestBlockIndex === -1) {
                return self::TREAT_AS_ANY_OTHER_END_TAG;
            }

            $furthestBlock = $stackOfOpenElements->getElements()[$furthestBlockIndex];
            $commonAncestor = $stackOfOpenElements->getElements()[
                array_search($formattingElement, $stackOfOpenElements->getElements()) - 1
            ];

            $bookmarkIndex = array_search($formattingElement, $listOfActiveFormattingElements->getElements());

            $nodeIndex = $furthestBlockIndex;
            $lastNode = $furthestBlock;

            $innerLoopCounter = 0;
            while (true) {
                $nodeIndex -= 1;
                $node = $stackOfOpenElements->getElements()[$nodeIndex];

                if ($node === $formattingElement) {
                    break;
                }
            }

            $this->getParentNodeOf($lastNode, $stackOfOpenElements)->removeChild($lastNode);
            $this->getAppropriatePlaceForInserting($lastNode, $commonAncestor, $stackOfOpenElements)->appendChild($lastNode);

            $newFormattingElement = clone $formattingElement;
            $newFormattingElement->removeChildren();

            foreach ($furthestBlock->getChildren() as $child) {
                $newFormattingElement->appendChild($child);
            }

            $furthestBlock->removeChildren();
            $furthestBlock->appendChild($newFormattingElement);

            $listOfActiveFormattingElements->removeElement($formattingElement);
            $listOfActiveFormattingElements->addAtPosition($newFormattingElement, $bookmarkIndex);

            $stackOfOpenElements->removeElement($formattingElement);
            $stackOfOpenElements->insertNodeAtPosition(
                $newFormattingElement,
                array_search($furthestBlock, $stackOfOpenElements->getElements()) + 1
            );
        }
    }

    /**
     * Returns the furthest block from the element. (For algorithm explanation see HTML spec.)
     *
     * @param ElementNode $element
     * @param StackOfOpenElements $stackOfOpenElements
     * @return int
     */
    private function getFurthestBlockIndex(ElementNode $element, StackOfOpenElements $stackOfOpenElements)
    {
        $elementKey = array_search($element, $stackOfOpenElements->getElements());

        for ($i = $elementKey; $i < count($stackOfOpenElements->getElements()); $i++) {
            // TODO Clean up DomBuilder instance creation
            if ((new DomBuilder())->isSpecialTag($stackOfOpenElements->getElements()[$i]->getName())) {
                return $i;
            }
        }

        return -1;
    }

    /**
     * Returns the parent element of a node.
     *
     * @param ElementNode $node
     * @param StackOfOpenElements $stackOfOpenElements
     * @return ElementNode
     */
    private function getParentNodeOf(ElementNode $node, StackOfOpenElements $stackOfOpenElements)
    {
        $reversedStackOfOpenElements = array_reverse($stackOfOpenElements->getElements());

        foreach ($reversedStackOfOpenElements as $stackElement) {
            if (in_array($node, $stackElement->getChildren())) {
                return $stackElement;
            }
        }

        return null;
    }

    /**
     * Determine where to appropriately insert an element.
     *
     * @param ElementNode $element
     * @param ElementNode $overrideTarget
     * @return int
     */
    public function getAppropriatePlaceForInserting($element, $overrideTarget, StackOfOpenElements $stackOfOpenElements)
    {
        return $stackOfOpenElements->getElements()[
            array_search($overrideTarget, $stackOfOpenElements->getElements()) + 1
        ];
    }
}
