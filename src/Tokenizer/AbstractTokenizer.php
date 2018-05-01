<?php
namespace HtmlParser\Tokenizer;

use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\State;
use HtmlParser\Tokenizer\TokenListener;
use HtmlParser\Tokenizer\Tokens\Token;
use HtmlParser\Tokenizer\Tokens\EndOfFileToken;

abstract class AbstractTokenizer implements Tokenizer
{
    /**
     * @var Token
     */
    protected $currentToken;

    /**
     * @var string[]
     */
    protected $characters;

    /**
     * @var int
     */
    protected $characterIndex;

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
    public function tokenize($htmlString)
    {
        $this->characters = $htmlString;
        $stringLength = mb_strlen($this->characters, '8bit');

        for ($this->characterIndex = 0; $this->characterIndex < $stringLength; $this->characterIndex += 1) {
            $currentCharacter = $this->characters[$this->characterIndex];
            $this->state->processCharacter($currentCharacter, $this);
        }
    }

    /**
     * @inheritdoc
     */
    public function getNextCharacters($amount)
    {
        $result = '';

        //TODO throw error if more characters are wanted than are left.

        for ($counter = $this->characterIndex + 1;
            $counter <= $this->characterIndex + $amount && $counter < mb_strlen($this->characters, '8bit');
            $counter += 1) {
            $result .= $this->characters[$counter];
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function consumeNextCharacters($amount)
    {
        $this->characterIndex += $amount;
    }

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
    public function setToken(Token $currentToken)
    {
        $this->currentToken = $currentToken;
    }

    /**
     * @return Token
     */
    public function getToken()
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
    public function emitEofToken()
    {
        $this->emitToken(new EndOfFileToken());
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
