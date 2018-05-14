<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class HexadecimalCharacterReferenceStartState extends AbstractCharacterReferenceState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        if (preg_match('/[0-9a-fA-F]/', $character)) {
            $tokenizer->setState(new HexadecimalCharacterReferenceState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        } else {
            // TODO error
            $this->flushCodePoints($tokenizer);

            $tokenizer->setState($tokenizer->getReturnState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        }
    }
}
