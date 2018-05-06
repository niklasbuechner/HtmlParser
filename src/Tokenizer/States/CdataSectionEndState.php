<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class CdataSectionEndState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case ']':
                $tokenizer->emitToken(new CharacterToken(']'));
                break;

            case '>':
                $tokenizer->setState(new DataState());
                break;

            default:
                $tokenizer->emitToken(new CharacterToken(']'));
                $tokenizer->emitToken(new CharacterToken(']'));
                $tokenizer->setState(new CdataSectionState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
