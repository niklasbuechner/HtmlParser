<?php
namespace HtmlParser\Tokenizer;

use HtmlParser\Tokenizer\States\State;
use HtmlParser\Tokenizer\TokenListener;
use HtmlParser\Tokenizer\Tokens\Token;

abstract class AbstractTokenizer implements Tokenizer
{
    /**
     * @var Token
     */
    protected $currentToken;

    /**
     * @var int
     */
    protected $characterReferenceCode;

    /**
     * @var TokenListener
     */
    protected $listener;

    /**
     * @var State
     */
    protected $returnState;

    /**
     * @var State
     */
    protected $state;

    /**
     * @var string
     */
    protected $temporaryBuffer;

    /**
     * @inheritdoc
     */
    abstract public function tokenize($htmlString);

    public function __construct(TokenListener $tokenListener)
    {
        $this->listener = $tokenListener;
    }

    /**
     * @inheritdoc
     */
    public function setCharacterReferenceCode($characterReferenceCode)
    {
        $this->characterReferenceCode = $characterReferenceCode;
    }

    /**
     * @return int
     */
    public function getCharacterReferenceCode()
    {
        return $this->characterReferenceCode;
    }

    /**
     * @inheritdoc
     */
    public function setReturnState(State $state)
    {
        $this->returnState = $state;
    }

    /**
     * @return State
     */
    public function getReturnState()
    {
        return $this->returnState;
    }

    /**
     * @inheritdoc
     */
    public function setState(State $state)
    {
        $this->state = $state;
    }

    /**
     * @return $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @inheritdoc
     */
    public function setCurrentToken(Token $currentToken)
    {
        $this->currentToken = $currentToken;
    }

    /**
     * @return Token
     */
    public function getCurrentToken()
    {
        return $this->currentToken;
    }

    /**
     * @inheritdoc
     */
    public function emitCurrentToken()
    {
        $this->emitToken($this->currentToken);
    }

    /**
     * @inheritdoc
     */
    public function emitToken(Token $token)
    {
        $token->prepareEmit();
        $this->listener->emitToken($token);
    }

    /**
     * @inheritdoc
     */
    public function appendToTemporaryBuffer($character)
    {
        $this->temporaryBuffer .= $character;
    }

    /**
     * @inheritdoc
     */
    public function getTemporaryBuffer()
    {
        return $this->temporaryBuffer;
    }
}
