<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class RawTextLessThanSignState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                $tokenizer->clearTemporaryBuffer();
                $tokenizer->setState(new RawTextEndTagOpenState());
                break;

            default:
                $tokenizer->emitToken(new CharacterToken('<'));
                $tokenizer->setState(new RawTextState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
