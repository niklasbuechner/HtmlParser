<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class NumericCharacterReferenceEndState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        // TODO
        $character = html_entity_decode('&#' . $tokenizer->getCharacterReferenceCode() . ';');

        $tokenizer->setState($tokenizer->getReturnState());
        $tokenizer->getState()->processCharacter($character, $tokenizer);
    }
}
