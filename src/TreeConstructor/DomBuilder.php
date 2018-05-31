<?php
namespace HtmlParser\TreeConstructor;

use HtmlParser\TreeConstructor\DomBuilder\ActiveFormattingMarker;
use HtmlParser\TreeConstructor\Nodes\CommentNode;
use HtmlParser\TreeConstructor\Nodes\DocumentNode;
use HtmlParser\TreeConstructor\Nodes\ElementNode;
use HtmlParser\TreeConstructor\Nodes\TextNode;

class DomBuilder
{
    /**
     * @var ElementNode[]
     */
    private $listOfActiveFormattingElements;

    /**
     * @var ElementNode[]
     */
    private $stackOfOpenElements;

    /**
     * @var boolean
     */
    private $framesetOkayFlag;

    /**
     * @var string[]
     */
    private $tagsWithImpliedEndTags;

    public function __construct()
    {
        $this->stackOfOpenElements = [new DocumentNode()];
        $this->listOfActiveFormattingElements = [];
        $this->framesetOkayFlag = true;
        $this->tagsWithImpliedEndTags = [
            'caption',
            'colgroup',
            'dd',
            'dt',
            'li',
            'optgroup',
            'option',
            'p',
            'rb',
            'rp',
            'rt',
            'rtc',
            'tboday',
            'td',
            'tfoot',
            'th',
            'thead',
            'tr',
        ];
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
     * Returns whether frameset elements may be pushed onto the stack right now.
     *
     * @return @boolean
     */
    public function getFramesetOkayFlag()
    {
        return $this->framesetOkayFlag;
    }

    /**
     * Set the frameset okay flag.
     *
     * @param boolean $framesetOkayFlag
     */
    public function setFramesetOkayFlag($framesetOkayFlag)
    {
        $this->framesetOkayFlag = $framesetOkayFlag;
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
     * Returns the list of active formatting elements.
     *
     * @return ElementNode[]
     */
    public function getListOfActiveFormattingElements()
    {
        return $this->listOfActiveFormattingElements;
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
            return array_pop($this->stackOfOpenElements);
        }

        return null;
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

    /**
     * Adds a marker to the list of active formatting elements.
     */
    public function pushMarkerOntoListOfActiveFormattingElements()
    {
        $this->listOfActiveFormattingElements[] = new ActiveFormattingMarker();
    }

    /**
     * Adds an element onto the list of active formatting elements.
     *
     * @param ElementNode $element
     */
    public function pushElementOntoListOfActiveFormattingElements(ElementNode $element)
    {
        $this->listOfActiveFormattingElements[] = $element;
    }

    /**
     * Generate end tags for certain elements.
     */
    public function generateImpliedEndTagsThoroughly()
    {
        while (in_array($this->getCurrentNode()->getName(), $this->tagsWithImpliedEndTags)) {
            $this->popLastElementOfStackOfOpenElements();
        }
    }

    /**
     * Clears the list of active formating elements until it hits a marker.
     */
    public function clearListOfActiveFormattingElementsToNextMarker()
    {
        while (!(array_pop($this->listOfActiveFormattingElements) instanceof ActiveFormattingMarker)) { // phpcs:ignore
            // The condition does the job
        }
    }
}
