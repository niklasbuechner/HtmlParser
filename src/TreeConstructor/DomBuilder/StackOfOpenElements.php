<?php
namespace HtmlParser\TreeConstructor\DomBuilder;

use Exception;
use HtmlParser\TreeConstructor\Nodes\DocumentNode;
use HtmlParser\TreeConstructor\Nodes\ElementNode;

class StackOfOpenElements
{
    /**
     * @var ElementNode[]
     */
    private $openElements;

    public function __construct()
    {
        $this->openElements = [new DocumentNode()];
    }

    /**
     * Pushes an element onto the stack of open elements.
     *
     * @param ElementNode $element
     */
    public function add(ElementNode $element)
    {
        $this->openElements[] = $element;
    }

    /**
     * Checks if an element is in the stack of open elements.
     *
     * @param ElementNode $element
     * @return true
     */
    public function contains($element)
    {
        $stackOfOpenElements = array_reverse($this->openElements);

        foreach ($stackOfOpenElements as $elementInStack) {
            if ($element === $elementInStack) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if a tag with given tag name exists on the stack of open elements.
     *
     * @param string $tagName
     * @param boolean
     */
    public function containsTagName($tagName)
    {
        $stackOfOpenElements = array_reverse($this->openElements);

        foreach ($stackOfOpenElements as $node) {
            if ($node instanceof ElementNode && $node->getName() === $tagName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if an element exists in a specific scope.
     *
     * @param string $tagName
     * @param string[] $blacklist
     * @return boolean
     */
    public function containsTagNameInScope($tagName, $blacklist)
    {
        $stackOfOpenElements = array_reverse($this->openElements);

        foreach ($stackOfOpenElements as $elementInStack) {
            if ($elementInStack->getName() === $tagName) {
                return true;
            }

            if (in_array($elementInStack->getName(), $blacklist)) {
                return false;
            }
        }

        return false;
    }

    /**
     * Returns the current node to work with.
     *
     * @return Node
     */
    public function getCurrentNode()
    {
        return $this->openElements[count($this->openElements) - 1];
    }

    /**
     * @return ElementNode[]
     */
    public function getElements()
    {
        return $this->openElements;
    }

    /**
     * Inserts a node at the correct place.
     *
     * @param ElementNode $node
     */
    public function insertNode(ElementNode $node)
    {
        $this->getCurrentNode()->appendChild($node);
        $this->add($node);
    }

    /**
     * Removes an element from the stack of open elements.
     *
     * @param ElementNode $element
     */
    public function removeElement(ElementNode $element)
    {
        $elementIndex = array_search($element, $this->openElements);

        if ($elementIndex === false) {
            return;
        }

        unset($this->openElements[$elementIndex]);
        $this->openElements = array_values($this->openElements);
    }

    /**
     * Pops the last element of the stack of open elements.
     *
     * @return ElementNode
     */
    public function pop()
    {
        // Do not pop document node off of stack of open elements.
        if (count($this->openElements) === 1) {
            throw new Exception('You can not pop the document node of the stack of open elements.');
        }

        return array_pop($this->openElements);
    }

    /**
     * Pops all elements of the stack of open elements until an element with the given name
     * has been found. (Including the element with the given name.)
     *
     * @param string $name
     */
    public function popUntilElementWithName($name)
    {
        while ($this->pop()->getName() !== $name) { // phpcs:ignore
            // The condition does the job
        }
    }

    /**
     * Pops all elements of the stack of open elements until an element with the given name
     * has been found.
     *
     * @param ElementNode $element
     */
    public function popUntilElement($element)
    {
        while ($this->pop() !== $element) { // phpcs:ignore
            // The condition does the job
        }
    }
}
