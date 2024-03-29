<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataEscapeStartState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '-':
                $tokenizer->setState(new ScriptDataEscapeStartDashState());
                $tokenizer->emitToken(new CharacterToken('-'));
                break;

            default:
                $tokenizer->setState(new ScriptDataState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
