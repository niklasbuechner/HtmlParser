<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class HexadecimalCharacterReferenceStartState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        if (preg_match('/[0-9a-fA-F]/', $character)) {
            $tokenizer->setState(new HexadecimalCharacterReferenceState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        }
        // TODO
    }
}
