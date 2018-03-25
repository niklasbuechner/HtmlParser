<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

interface State
{
    /**
     * Processes the current character in this state.
     *
     * @param char $character
     * @param Tokenizer $tokenizer
     */
    public function processCharacter($character, Tokenizer $tokenizer);
}
