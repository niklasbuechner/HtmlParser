<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataEscapedDashState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '-':
                $tokenizer->setState(new ScriptDataEscapedDashDashState());
                $tokenizer->emitToken(new CharacterToken('-'));
                break;

            case '<':
                $tokenizer->setState(new ScriptDataEscapedLessThanSignState());
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $tokenizer->emitEofToken();
                break;

            default:
                $tokenizer->setState(new ScriptDataEscapedState());
                $tokenizer->emitToken(new CharacterToken($character));
                break;
        }
    }
}
