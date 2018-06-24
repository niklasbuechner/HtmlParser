<?php
namespace HtmlParser\TreeConstructor;

use Exception;
use HtmlParser\TreeConstructor\DomBuilder\ActiveFormattingMarker;
use HtmlParser\TreeConstructor\DomBuilder\Algorithms\AdoptionAgencyAlgorithm;
use HtmlParser\TreeConstructor\DomBuilder\Algorithms\ReconstructActiveFormattingList;
use HtmlParser\TreeConstructor\DomBuilder\ListOfActiveFormattingElements;
use HtmlParser\TreeConstructor\DomBuilder\StackOfOpenElements;
use HtmlParser\TreeConstructor\Nodes\CommentNode;
use HtmlParser\TreeConstructor\Nodes\DocumentNode;
use HtmlParser\TreeConstructor\Nodes\ElementNode;
use HtmlParser\TreeConstructor\Nodes\TextNode;

class DomBuilder
{
    /**
     * @var AdoptionAgencyAlgorithm
     */
    private $adoptionAgencyAlgorithm;

    /**
     * @var ElementNode
     */
    private $formPointer;

    /**
     * @var boolean
     */
    private $framesetOkayFlag;

    /**
     * @var ElementNode
     */
    private $headPointer;

    /**
     * @var ElementNode[]
     */
    private $listOfActiveFormattingElements;

    /**
     * @var ElementNode[]
     */
    private $stackOfOpenElements;

    /**
     * @var string[]
     */
    private $tagsToGenerateEndTags;

    /**
     * @var string[]
     */
    private $tagsToGenerateEndTagsThoroughly;

    /**
     * @var string[]
     */
    private $tagsWithSpecialParsingRules;

    public function __construct()
    {
        $this->adoptionAgencyAlgorithm = new AdoptionAgencyAlgorithm();
        $this->stackOfOpenElements = new StackOfOpenElements();
        $this->listOfActiveFormattingElements = new ListOfActiveFormattingElements();
        $this->framesetOkayFlag = true;
        $this->tagsToGenerateEndTags = [
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
        ];
        $this->tagsToGenerateEndTagsThoroughly = [
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
        $this->tagsWithSpecialParsingRules = [
            'address',
            'applet',
            'area',
            'article',
            'aside',
            'base',
            'basefont',
            'bgsound',
            'blockquote',
            'body',
            'br',
            'button',
            'caption',
            'center',
            'col',
            'colgroup',
            'dd',
            'details',
            'dir',
            'div',
            'dl',
            'dt',
            'embed',
            'fieldset',
            'figcaption',
            'figure',
            'footer',
            'form',
            'frame',
            'frameset',
            'h1',
            'h2',
            'h3',
            'h4',
            'h5',
            'h6',
            'head',
            'header',
            'hgroup',
            'hr',
            'html',
            'iframe',
            'img',
            'input',
            'keygen',
            'li',
            'link',
            'listing',
            'main',
            'marquee',
            'menu',
            'meta',
            'nav',
            'noembed',
            'noframes',
            'noscript',
            'object',
            'ol',
            'p',
            'param',
            'plaintext',
            'p',
            'script',
            'section',
            'select',
            'source',
            'style',
            'summary',
            'table',
            'tbody',
            'td',
            'template',
            'textarea',
            'tfoot',
            'th',
            'thead',
            'title',
            'tr',
            'track',
            'ul',
            'wpr',
            'xmp',
        ];
    }

    /**
     * Returns the current document node.
     *
     * @return DocumentNode
     */
    public function getDocumentNode()
    {
        return $this->stackOfOpenElements->getElements()[0];
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
     * Sets the head element to be the current node
     */
    public function pushHeadToStackOfOpenElements()
    {
        $this->stackOfOpenElements->add($this->getHeadPointer());
    }

    /**
     * Removes the head element from the stack of open elements.
     */
    public function removeHeadFromStackOfOpenElements()
    {
        while ($this->popLastElementOfStackOfOpenElements()->getName() !== 'head') { // phpcs:ignore
            // The condition does the work.
        }
    }

    /**
     * Set the head pointer to the current element.
     */
    public function setHeadPointerToCurrentNode()
    {
        if ($this->getCurrentNode()->getName() !== 'head') {
            throw new Exception('Can not set the current element as head pointer. It is not a `head` element!');
        }

        $this->headPointer = $this->getCurrentNode();
    }

    /**
     * @return ElementNode
     */
    public function getHeadPointer()
    {
        return $this->headPointer;
    }

    /**
     * Set the form pointer to the current element.
     */
    public function setFormPointerToCurrentNode()
    {
        if ($this->getCurrentNode()->getName() !== 'form') {
            throw new Exception('Can not set the current element as form pointer. It is not a `form` element!');
        }

        $this->formPointer = $this->getCurrentNode();
    }

    /**
     * @return ElementNode
     */
    public function getFormPointer()
    {
        return $this->formPointer;
    }

    /**
     * Sets the form pointer to null.
     */
    public function clearFormPointer()
    {
        $this->formPointer = null;
    }

    /**
     * @return Node[]
     */
    public function getStackOfOpenElements()
    {
        return $this->stackOfOpenElements->getElements();
    }

    /**
     * Returns the current node to work with.
     *
     * @return Node
     */
    public function getCurrentNode()
    {
        return $this->stackOfOpenElements->getCurrentNode();
    }

    /**
     * Returns the node before the given node.
     *
     * @param ElementNode[]
     * @return ElementNode
     */
    public function getParentNodeOf($node)
    {
        $stackOfOpenElements = array_reverse($this->getStackOfOpenElements());

        foreach ($stackOfOpenElements as $key => $nodeInStack) {
            if ($nodeInStack === $node) {
                return $stackOfOpenElements[$key + 1];
            }
        }

        throw new Exception('The given element is not in the stack of open elements.');
    }

    /**
     * Returns the list of active formatting elements.
     *
     * @return ElementNode[]
     */
    public function getListOfActiveFormattingElements()
    {
        return $this->listOfActiveFormattingElements->getElements();
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
        $this->stackOfOpenElements->insertNode($node);
    }

    /**
     * Removes an element from the stack of open elements.
     *
     * @param ElementNode $element
     */
    public function removeElementFromStackOfOpenElements(ElementNode $element)
    {
        $this->stackOfOpenElements->removeElement($element);
    }

    /**
     * Pops the last element of the stack of open elements.
     *
     * @return ElementNode
     */
    public function popLastElementOfStackOfOpenElements()
    {
        return $this->stackOfOpenElements->pop();
    }

    /**
     * Pops all elements of the stack of open elements until an element with the given name
     * has been found.
     *
     * @param string $name
     */
    public function popElementsOfStackOfOpenElementsUntilElementWithName($name)
    {
        $this->stackOfOpenElements->popUntilElementWithName($name);
    }

    /**
     * Pops all elements of the stack of open elements until an element with the given name
     * has been found.
     *
     * @param ElementNode $element
     */
    public function popElementsOfStackOfOpenElementsUntilElement($element)
    {
        $this->stackOfOpenElements->popUntilElement($element);
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

        $this->reconstructActiveFormattingList();
        $this->getCurrentNode()->getLastChild()->appendToData($character);
    }

    /**
     * Adds a marker to the list of active formatting elements.
     */
    public function pushMarkerOntoListOfActiveFormattingElements()
    {
        $this->listOfActiveFormattingElements->add(new ActiveFormattingMarker());
    }

    /**
     * Adds an element onto the list of active formatting elements.
     *
     * @param ElementNode $element
     */
    public function pushElementOntoListOfActiveFormattingElements(ElementNode $element)
    {
        $this->listOfActiveFormattingElements->add($element);
    }

    /**
     * Checks if an element is is in the list of active formatting elements before a marker is found.
     *
     * @param string $tagName
     * @return boolean
     */
    public function doesListOfActiveFormattingElementsContainBeforeMarker($tagName)
    {
        return $this->listOfActiveFormattingElements->containsTagNameBeforeMarker($tagName);
    }

    /**
     * Reconstructs the list of active formatting elements.
     */
    public function reconstructActiveFormattingList()
    {
        ReconstructActiveFormattingList::reconstructActiveFormattingList(
            $this->listOfActiveFormattingElements,
            $this->stackOfOpenElements
        );
    }

    /**
     * Clears the list of active formating elements until it hits a marker.
     */
    public function clearListOfActiveFormattingElementsToNextMarker()
    {
        $this->listOfActiveFormattingElements->clearUntilMarker();
    }

    /**
     * Generate end tags for certain elements.
     *
     * @param string[] $blacklist
     */
    public function generateImpliedEndTags($blacklist = [])
    {
        $tagsToGenerateEndTagsFor = array_diff($this->tagsToGenerateEndTags, $blacklist);

        while (in_array($this->getCurrentNode()->getName(), $tagsToGenerateEndTagsFor)) {
            $this->popLastElementOfStackOfOpenElements();
        }
    }

    /**
     * Generate end tags for certain elements.
     */
    public function generateImpliedEndTagsThoroughly()
    {
        while (in_array($this->getCurrentNode()->getName(), $this->tagsToGenerateEndTagsThoroughly)) {
            $this->popLastElementOfStackOfOpenElements();
        }
    }

    /**
     * Transfers all attributes of one elemnt onto another.
     *
     * @param ElementNode|Token $sourceNode
     * @param ElementNode|Token $destinationNode
     */
    public function transferAttributes($sourceNode, $destinationNode)
    {
        foreach ($sourceNode->getAttributes() as $attribute) {
            $destinationNode->addAttribute($attribute);
        }
    }

    /**
     * Checks if a tag with given tag name exists on the stack of open elements.
     *
     * @param string $tagName
     * @param boolean
     */
    public function containsStackOfOpenElements($tagName)
    {
        return $this->stackOfOpenElements->containsTagName($tagName);
    }

    /**
     * Checks if an element is in the stack of open elements.
     *
     * @param ElementNode $element
     * @return true
     */
    public function stackOfOpenElementsContainsElement($element)
    {
        return $this->stackOfOpenElements->contains($element);
    }

    /**
     * Checks if an element exists in a specific scope.
     *
     * @param string $tagName
     * @param string[] $unwantedTagNames
     * @return boolean
     */
    public function stackOfOpenElementsContainsElementInScope($tagName, $unwantedTagNames)
    {
        return $this->stackOfOpenElements->containsTagNameInScope($tagName, $unwantedTagNames);
    }

    /**
     * Checks if an element exists in button scope.
     *
     * @param string $tagName
     * @return boolean
     */
    public function stackOfOpenElementsContainsElementInButtonScope($tagName)
    {
        return $this->stackOfOpenElementsContainsElementInScope($tagName, [
            'applet',
            'caption',
            'html',
            'table',
            'td',
            'th',
            'marquee',
            'object',
            'template',
            'button',
        ]);
    }

    /**
     * Checks if a tag has some kind of special parsing rules associate.
     *
     * @param string $tagName
     * @param string[] $blacklist
     * @return boolean
     */
    public function isSpecialTag($tagName, $blacklist = [])
    {
        $specialTags = array_diff($this->tagsWithSpecialParsingRules, $blacklist);

        return in_array($tagName, $specialTags);
    }

    /**
     * Runs the adoption agency algorithm for a token.
     *
     * @param Token $token
     * @param InsertionMode $insertionMode
     */
    public function runAdoptionAgencyAlgorithm($token, $insertionMode)
    {
        if ($this->adoptionAgencyAlgorithm->runAdoptionAgencyAlgorithm($token, $this->stackOfOpenElements, $this->listOfActiveFormattingElements) === AdoptionAgencyAlgorithm::TREAT_AS_ANY_OTHER_END_TAG) {
            $node = $this->getCurrentNode();

            while (true) {
                if ($node->getName() === $token->getName()) {
                    $this->generateImpliedEndTags([$token->getName()]);
                    $this->stackOfOpenElements->popUntilElement($node);

                    return;
                } elseif ($this->isSpecialTag($node->getName())) {
                    return;
                }

                $node = $this->getCurrentNode();
            }
        }
    }
}
