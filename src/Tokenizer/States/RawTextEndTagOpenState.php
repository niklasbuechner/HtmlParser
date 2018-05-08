<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;

class RawTextEndTagOpenState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        if (preg_match('/[a-z]/', $character)) {
            $tokenizer->setToken(new EndTagToken());
            $tokenizer->setState(new RawTextEndTagNameState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        } else {
            $tokenizer->emitToken(new CharacterToken('<'));
            $tokenizer->emitToken(new CharacterToken('/'));
            $tokenizer->setState(new RawTextState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        }
    }
}
