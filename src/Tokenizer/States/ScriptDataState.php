<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '<':
                $tokenizer->setState(new ScriptDataLessThanSignState());
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
