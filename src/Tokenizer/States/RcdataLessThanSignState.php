<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class RcdataLessThanSignState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                $tokenizer->clearTemporaryBuffer();
                $tokenizer->setState(new RcdataEndTagOpenState());
                break;

            default:
                $tokenizer->emitToken(new CharacterToken('<'));
                $tokenizer->setState(new RcdataState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
