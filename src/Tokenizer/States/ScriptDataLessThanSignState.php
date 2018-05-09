<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataLessThanSignState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                $tokenizer->clearTemporaryBuffer();
                $tokenizer->setState(new ScriptDataEndTagOpenState());
                break;

            case '!':
                $tokenizer->setState(new ScriptDataEscapeStartState());
                $tokenizer->emitToken(new CharacterToken('<'));
                $tokenizer->emitToken(new CharacterToken('!'));
                break;

            default:
                $tokenizer->emitToken(new CharacterToken('<'));
                $tokenizer->setState(new ScriptDataState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
