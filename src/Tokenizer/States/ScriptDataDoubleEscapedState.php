<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataDoubleEscapedState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '-':
                $tokenizer->setState(new ScriptDataDoubleEscapedDashState());
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
                $tokenizer->emitToken(new CharacterToken($character));
                break;
        }
    }
}
