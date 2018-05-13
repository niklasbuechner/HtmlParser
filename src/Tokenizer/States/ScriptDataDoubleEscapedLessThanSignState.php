<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataDoubleEscapedLessThanSignState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                $tokenizer->clearTemporaryBuffer();
                $tokenizer->setState(new ScriptDataDoubleEscapeEndState());
                $tokenizer->emitToken(new CharacterToken('/'));
                break;

            default:
                $tokenizer->setState(new ScriptDataDoubleEscapedState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
