<?php
namespace HtmlParser\TreeConstructor\DomBuilder\Algorithms;

use HtmlParser\TreeConstructor\DomBuilder\ListOfActiveFormattingElements;
use HtmlParser\TreeConstructor\DomBuilder\StackOfOpenElements;

class ReconstructActiveFormattingList
{
    /**
     * Reconstructs the list of active formatting elements.
     *
     * @param ListOfActiveFormattingElements $listOfActiveFormattingElements
     * @param StackOfOpenElements $stackOfOpenElements
     */
    public static function reconstructActiveFormattingList(
        ListOfActiveFormattingElements $listOfActiveFormattingElements,
        StackOfOpenElements $stackOfOpenElements
    ) {
        if (count($listOfActiveFormattingElements->getElements()) === 0) {
            return;
        }

        $reversetActiveFormattingList = array_reverse($listOfActiveFormattingElements->getElements());
        $entry = $reversetActiveFormattingList[0];

        if ($entry instanceof ActiveFormattingMarker || $stackOfOpenElements->containsTagName($entry->getName())) {
            return;
        }

        self::handlePossibleLastElement($entry, $reversetActiveFormattingList, 0, $stackOfOpenElements);
    }

    /**
     * Checks if the element is the last element in the list of active formatting elements
     * and handles its processing.
     *
     * @param ElementNode $entry
     * @param ElementNode[] $reverseActiveFormattingList
     * @param int $lastIndex
     * @param StackOfOpenElements $stackOfOpenElements
     */
    private static function handlePossibleLastElement($entry, $reversetActiveFormattingList, $lastIndex, $stackOfOpenElements)
    {
        if (count($reversetActiveFormattingList) === $lastIndex + 1) {
            self::reinsertElement($entry, $reversetActiveFormattingList, $lastIndex, $stackOfOpenElements);
        } else {
            self::advanceOneElement($reversetActiveFormattingList, $lastIndex, $stackOfOpenElements);
        }
    }

    /**
     * Walks back one element in the list of active formatting elements in order to find the end
     * of the list or a marker to start reopening the not yet closed tags.
     *
     * @param ElementNode[] $reverseActiveFormattingList
     * @param int $lastIndex
     * @param StackOfOpenElements $stackOfOpenElements
     */
    private static function advanceOneElement($reversetActiveFormattingList, $lastIndex, $stackOfOpenElements)
    {
        $lastIndex += 1;
        $entry = $reversetActiveFormattingList[$lastIndex];

        if ($entry instanceof ActiveFormattingMarker || $stackOfOpenElements->containsTagName($entry->getName())) {
            $lastIndex -= 1;
            $entry = $reversetActiveFormattingList[$lastIndex];
            self::reinsertElement($entry, $reversetActiveFormattingList, $lastIndex, $stackOfOpenElements);
        } else {
            self::handlePossibleLastElement($entry, $reversetActiveFormattingList, $lastIndex, $stackOfOpenElements);
        }
    }

    /**
     * Reinserts an active formatting element into the stack of open elements and recursivly reinserts
     * all elements after that.
     *
     * @param ElementNode $entry
     * @param ElementNode[] $reverseActiveFormattingList
     * @param int $lastIndex
     * @param StackOfOpenElements $stackOfOpenElements
     */
    private static function reinsertElement($entry, $reversetActiveFormattingList, $lastIndex, $stackOfOpenElements)
    {
        $newEntry = clone $entry;
        $stackOfOpenElements->insertNode($newEntry);
        $reversetActiveFormattingList[$lastIndex] = $newEntry;

        if ($lastIndex !== 0) {
            $lastIndex -= 1;
            self::reinsertElement($reversetActiveFormattingList[$lastIndex], $reversetActiveFormattingList, $lastIndex, $stackOfOpenElements);
        }
    }
}
