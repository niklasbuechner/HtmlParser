<?php
namespace HtmlParser\Tests\TestResources;

use HtmlParser\Tokenizer\TokenListener;
use HtmlParser\Tokenizer\Tokens\Token;

class TestTokenListener implements TokenListener
{
    /**
     * @var Token[]
     */
    private $tokens = [];

    /**
     * @inheritdoc
     */
    public function emitToken(Token $token)
    {
        $this->tokens[] = $token;
    }

    /**
     * @return Token[]
     */
    public function getEmittedTokens()
    {
        return $this->tokens;
    }
}
