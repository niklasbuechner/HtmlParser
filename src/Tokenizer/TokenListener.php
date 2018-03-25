<?php
namespace HtmlParser\Tokenizer;

use HtmlParser\Tokenizer\Tokens\Token;

interface TokenListener
{
    /**
     * Every time a token is emitted, this function is called.
     * Classes which process the emitted tokens should listen to this event.
     *
     * @param Token $token;
     */
    public function emitToken(Token $token);
}
