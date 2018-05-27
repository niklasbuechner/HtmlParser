<?php
namespace HtmlParser\TreeConstructor;

use HtmlParser\TreeConstructor\Nodes\CommentNode;
use HtmlParser\TreeConstructor\Nodes\DocumentNode;
use HtmlParser\TreeConstructor\Nodes\ElementNode;
use HtmlParser\TreeConstructor\Nodes\TextNode;

class DomBuilder
{
    /**
     * @var ElementNode[]
     */
    private $stackOfOpenElements;

    public function __construct()
    {
        $this->stackOfOpenElements = [new DocumentNode()];
    }

    /**
     * Returns the current document node.
     *
     * @return DocumentNode
     */
    public function getDocumentNode()
    {
        return $this->stackOfOpenElements[0];
    }

    /**
     * @return Node[]
     */
    public function getStackOfOpenElements()
    {
        return $this->stackOfOpenElements;
    }

    /**
     * Returns the current node to work with.
     *
     * @return Node
     */
    public function getCurrentNode()
    {
        return $this->stackOfOpenElements[count($this->stackOfOpenElements) - 1];
    }

    /**
     * Adds a comment to the document node object.
     *
     * @param CommentNode $commentToken
     */
    public function addComment(CommentNode $commentNode)
    {
        $this->getCurrentNode()->appendChild($commentNode);
    }

    /**
     * Inserts a node at the correct place.
     *
     * @param ElementNode $node
     */
    public function insertNode(ElementNode $node)
    {
        $this->getCurrentNode()->appendChild($node);
        $this->addElementToStackOfOpenElements($node);
    }

    /**
     * Pushs an element onto the stack of open elements.
     *
     * @param ElementNode $node
     */
    public function addElementToStackOfOpenElements(ElementNode $node)
    {
        $this->stackOfOpenElements[] = $node;
    }

    /**
     * Pops the last element of the stack of open elements.
     */
    public function popLastElementOfStackOfOpenElements()
    {
        // Do not pop document node off of stack of open elements.
        if (count($this->stackOfOpenElements) > 1) {
            array_pop($this->stackOfOpenElements);
        }
    }

    /**
     * Insert a character to its correct position.
     *
     * @param string $character
     */
    public function insertCharacter($character)
    {
        if ($this->getCurrentNode() instanceof DocumentNode) {
            return;
        }

        if (!$this->getCurrentNode()->getLastChild() instanceof TextNode) {
            $this->getCurrentNode()->appendChild(new TextNode($character));

            return;
        }

        $this->getCurrentNode()->getLastChild()->appendToData($character);
    }
}
