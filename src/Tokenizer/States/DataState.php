<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class DataState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case "<":
                $tokenizer->setState(new TagOpenState());
                break;

            case "&":
                $tokenizer->setReturnState($this);
                $tokenizer->setState(new CharacterReferenceState($tokenizer));
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
