<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataDoubleEscapedDashState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '-':
                $tokenizer->setState(new ScriptDataDoubleEscapedDashDashState());
                $tokenizer->emitToken(new CharacterToken('-'));
                break;

            case '<':
                $tokenizer->setState(new ScriptDataDoubleEscapedLessThanSignState());
                $tokenizer->emitToken(new CharacterToken('<'));
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $tokenizer->emitEofToken();
                break;

            default:
                $tokenizer->setState(new ScriptDataDoubleEscapedState());
                $tokenizer->emitToken(new CharacterToken($character));
                break;
        }
    }
}
