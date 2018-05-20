<?php
namespace HtmlParser\TreeConstructor;

use HtmlParser\Tokenizer\TokenListener;
use HtmlParser\Tokens\Token;
use HtmlParser\TreeConstructor\InsertionModes\InsertionMode;

class TreeConstructor implements TokenListener
{
    /**
     * @var InsertionMode
     */
    private $insertionMode;

    public function __construct()
    {
        $this->insertionMode = new InitialInsertionMode();
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
    public function setMode(InsertionMode $insertionMode)
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
}
