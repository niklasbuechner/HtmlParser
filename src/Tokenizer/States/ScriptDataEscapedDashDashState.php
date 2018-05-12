<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataEscapedDashDashState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '-':
                $tokenizer->emitToken(new CharacterToken('-'));
                break;

            case '<':
                $tokenizer->setState(new ScriptDataEscapedLessThanSignState());
                break;

            case '>':
                $tokenizer->setState(new ScriptDataState());
                $tokenizer->emitToken(new CharacterToken('>'));
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
