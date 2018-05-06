<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class RcdataState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '&':
                $tokenizer->setReturnState($this);
                $tokenizer->setState(new CharacterReferenceState($tokenizer));
                break;

            case '<':
                $tokenizer->setState(new RcdataLessThanSignState());
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
