<?php
namespace HtmlParser\TreeConstructor;

use HtmlParser\Tokenizer\TokenListener;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\Tokenizer\Tokens\Token;
use HtmlParser\TreeConstructor\InsertionModes\InitialInsertionMode;
use HtmlParser\TreeConstructor\InsertionModes\InsertionMode;
use HtmlParser\TreeConstructor\Nodes\CommentNode;
use HtmlParser\TreeConstructor\Nodes\DocumentNode;
use HtmlParser\TreeConstructor\Nodes\ElementNode;

class TreeConstructor implements TokenListener
{
    /**
     * @var InsertionMode
     */
    private $insertionMode;

    /**
     * @var DocumentNode
     */
    private $document;

    /**
     * @var ElementNode[]
     */
    private $stackOfOpenElements;

    public function __construct()
    {
        $this->insertionMode = new InitialInsertionMode();
        $this->document = new DocumentNode();
        $this->stackOfOpenElements = [];
    }

    /**
     * @inheritdoc
     */
    public function emitToken(Token $token)
    {
        $this->insertionMode->processToken($token, $this);
    }

    /**
     * Sets the current insertion mode to parse the next tokens with.
     *
     * @param InsertionMode $insertionMode
     */
    public function setInsertionMode(InsertionMode $insertionMode)
    {
        $this->insertionMode = $insertionMode;
    }

    /**
     * Returns the insertion mode the next token should be parsed with.
     *
     * @return InsertionMode
     */
    public function getInsertionMode()
    {
        return $this->insertionMode;
    }

    /**
     * Returns the current document node.
     *
     * @return DocumentNode
     */
    public function getDocumentNode()
    {
        return $this->document;
    }

    /**
     * Adds a comment to the document node object.
     *
     * @param CommentToken $commentToken
     */
    public function addComment(CommentToken $commentToken)
    {
        $this->document->appendChild(new CommentNode($commentToken->getData()));
    }

    /**
     * Creates a node form a start tag token.
     *
     * @param StartTagToken $token
     * @return ElementNode
     */
    public function createElementFromToken(StartTagToken $token)
    {
        return new ElementNode($token->getName(), $this->getDocumentNode(), $token->getAttributes());
    }

    /**
     * Creates a node from a tag name.
     *
     * @param $name
     * @return ElementNode
     */
    public function createElementFromTagName($name)
    {
        return new ElementNode($name, $this->getDocumentNode());
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
     * @return ElementNode[]
     */
    public function getStackOfOpenElements()
    {
        return $this->stackOfOpenElements;
    }
}
