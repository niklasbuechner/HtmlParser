<?php
namespace HtmlParser\TreeConstructor\DomBuilder;

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
}
