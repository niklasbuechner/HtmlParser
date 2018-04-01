<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\EndOfFileToken;

class DataState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case "&":
                $tokenizer->setReturnState($this);
                $tokenizer->setState(new CharacterReferenceState($tokenizer));
                break;
            case "<":
                $tokenizer->setState(new TagOpenState());
                break;
            case Tokenizer::END_OF_FILE_CHARACTER:
                $tokenizer->emitToken(new EndOfFileToken());
                break;
            default:
                $tokenizer->emitToken(new CharacterToken($character));
                break;
        }
    }
}
