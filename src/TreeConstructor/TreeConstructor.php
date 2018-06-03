<?php
namespace HtmlParser\TreeConstructor;

use HtmlParser\Tokenizer\TokenListener;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\Tokenizer\Tokens\Token;
use HtmlParser\TreeConstructor\InsertionModes\InHeadInsertionMode;
use HtmlParser\TreeConstructor\InsertionModes\InitialInsertionMode;
use HtmlParser\TreeConstructor\InsertionModes\InsertionMode;
use HtmlParser\TreeConstructor\Nodes\CommentNode;
use HtmlParser\TreeConstructor\Nodes\DocumentNode;
use HtmlParser\TreeConstructor\Nodes\ElementNode;
use HtmlParser\TreeConstructor\Tokenizer;

class TreeConstructor implements TokenListener
{
    /**
     * @var DomBuilder
     */
    private $domBuilder;

    /**
     * @var ElementFactory
     */
    private $elementFactory;

    /**
     * @var string
     */
    private $ignoreNewLineToken;

    /**
     * @var InsertionMode
     */
    private $insertionMode;

    /**
     * @var InsertionMode
     */
    private $originalInsertionMode;

    /**
     * @var array
     */
    private $stackOfTemplateInsertionModes;

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    public function __construct($domBuilder = null)
    {
        $this->domBuilder = $domBuilder ?: new DomBuilder();
        $this->elementFactory = new ElementFactory();
        $this->insertionMode = new InitialInsertionMode();
        $this->stackOfOpenElements = [];
        $this->stackOfTemplateInsertionModes = [];
    }

    /**
     * @inheritdoc
     */
    public function emitToken(Token $token)
    {
        if ($this->ignoreNewLineToken) {
            $this->ignoreNewLineToken = false;

            if ($token instanceof CharacterToken && $token->getCharacter() === '\n') {
                return;
            }
        }

        $this->insertionMode->processToken($token, $this, $this->elementFactory, $this->domBuilder);
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
     * @param InsertionMode $insertionMode
     */
    public function setOriginalInsertionMode(InsertionMode $insertionMode)
    {
        $this->originalInsertionMode = $insertionMode;
    }

    /**
     * @return InsertionMode
     */
    public function getOriginalInsertionMode()
    {
        return $this->originalInsertionMode;
    }

    /**
     * @param Tokenizer $tokenizer
     */
    public function setTokenizer(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * @return Tokenizer
     */
    public function getTokenizer()
    {
        return $this->tokenizer;
    }

    /**
     * Prevents the next token from being processed, if its a character token
     * with a line break.
     */
    public function skipCharacterLineBreakToken()
    {
        $this->ignoreNewLineToken = true;
    }

    /**
     * @return array
     */
    public function getStackOfTemplateInsertionModes()
    {
        return  $this->stackOfTemplateInsertionModes;
    }

    /**
     * @param InsertionMode $insertionMode
     */
    public function addInsertionModeToStackOfTemplateInsertionModes(InsertionMode $insertionMode)
    {
        $this->stackOfTemplateInsertionModes[] = $insertionMode;
    }

    /**
     * @return InsertionMode
     */
    public function popCurrentTemplateInsertionMode()
    {
        return array_pop($this->stackOfTemplateInsertionModes);
    }

    /**
     * Determines the insertion mode and resets.
     */
    public function resetInsertionMode()
    {
        $this->setInsertionMode(new InHeadInsertionMode());
    }
}
