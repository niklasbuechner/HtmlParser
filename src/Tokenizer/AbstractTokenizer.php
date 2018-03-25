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
     * @var TokenListener
     */
    protected $listener;

    /**
     * @var State
     */
    protected $state;

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
        $this->listener->emitToken($this->currentToken);
    }
}
