<?php
namespace HtmlParser\TreeConstructor\DomBuilder;

use HtmlParser\TreeConstructor\Nodes\ElementNode;

class ListOfActiveFormattingElements
{
    /**
     * @var ElementNode[]
     */
    private $elementList;

    public function __construct()
    {
        $this->elementList = [];
    }

    /**
     * Adds an element onto the list.
     *
     * @param ActiveFormattingMarker|ElementNode $element
     */
    public function add($element)
    {
        $this->elementList[] = $element;
    }

    /**
     * Adds an element onto the list at a specified position.
     * @param ElementNode $element
     * @param int $position
     */
    public function addAtPosition($element, $position)
    {
        $oldElementList = $this->elementList;
        $this->elementList = [];

        foreach ($oldElementList as $key => $listElement) {
            if ($key === $position) {
                $this->elementList[] = $element;
            }

            $this->elementList[] = $listElement;
        }
    }

    /**
     * Clears the list of active formating elements until it hits a marker.
     */
    public function clearUntilMarker()
    {
        while (!(array_pop($this->elementList) instanceof ActiveFormattingMarker)) { // phpcs:ignore
            // The condition does the job
        }
    }

    /**
     * Checks if an element is is in the list of active formatting elements before a marker is found.
     *
     * @param ElementNode $element
     * @return boolean
     */
    public function containsBeforeMarker(ElementNode $element)
    {
        $reverseElementList = array_reverse($this->elementList);

        foreach ($reverseElementList as $elementInList) {
            if ($elementInList === $element) {
                return true;
            }

            if ($elementInList instanceof ActiveFormattingMarker) {
                return false;
            }
        }

        return false;
    }

    /**
     * Checks if an element is is in the list of active formatting elements before a marker is found.
     *
     * @param string $tagName
     * @return boolean
     */
    public function containsTagNameBeforeMarker($tagName)
    {
        $reverseElementList = array_reverse($this->elementList);

        foreach ($reverseElementList as $elementInList) {
            if ($elementInList->getName() === $tagName) {
                return true;
            }

            if ($elementInList instanceof ActiveFormattingMarker) {
                return false;
            }
        }

        return false;
    }

    /**
     * @return ElementNode[]
     */
    public function getElements()
    {
        return $this->elementList;
    }

    /**
     * Returns the last added element in the list of active formatting elements with a given tag name
     * and before a marker.
     *
     * @param string $tagName
     * @return ElementNode
     */
    public function getLastElementWithTagNameBeforeMarker($tagName)
    {
        $reverseElementList = array_reverse($this->elementList);

        foreach ($reverseElementList as $elementInActiveElementList) {
            if ($elementInActiveElementList->getName() === $tagName) {
                return $elementInActiveElementList;
            }

            if ($elementInActiveElementList instanceof ActiveFormattingMarker) {
                return null;
            }
        }

        return null;
    }

    /**
     * Removes an element from the list of active formatting elements.
     *
     * @param ElementNode $element
     */
    public function removeElement(ElementNode $element)
    {
        $key = array_search($element, $this->elementList);

        if ($key === false) {
            return;
        }

        unset($this->elementList[$key]);
        $this->elementList = array_values($this->elementList);
    }

    /**
     * Replaces a node at a given position.
     *
     * @param ElementNode $node
     * @param int $position
     */
    public function replaceElementAtPosition(ElementNode $node, $position)
    {
        if ($position >= 0 && $position < count($this->elementList)) {
            $this->elementList[$position] = $node;
        }
    }
}
