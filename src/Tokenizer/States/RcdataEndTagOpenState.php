<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;

class RcdataEndTagOpenState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        if (preg_match('/[a-z]/', $character)) {
            $tokenizer->setToken(new EndTagToken());
            $tokenizer->setState(new RcdataEndTagNameState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        } else {
            $tokenizer->emitToken(new CharacterToken('<'));
            $tokenizer->emitToken(new CharacterToken('/'));
            $tokenizer->setState(new RcdataState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        }
    }
}
