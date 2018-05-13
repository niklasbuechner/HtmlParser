<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;

class ScriptDataEscapedEndTagOpenState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        if (preg_match('/[a-zA-Z]/', $character)) {
            $tokenizer->setToken(new EndTagToken());
            $tokenizer->setState(new ScriptDataEscapedEndTagNameState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        } else {
            $tokenizer->emitToken(new CharacterToken('<'));
            $tokenizer->emitToken(new CharacterToken('/'));
            $tokenizer->setState(new ScriptDataEscapedState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        }
    }
}
