<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class CdataSectionBracketState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case ']':
                $tokenizer->setState(new CdataSectionEndState());
                break;

            default:
                $tokenizer->emitToken(new CharacterToken(']'));
                $tokenizer->setState(new CdataSectionState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
