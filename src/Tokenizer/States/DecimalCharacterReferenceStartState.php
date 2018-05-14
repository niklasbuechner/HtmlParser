<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class DecimalCharacterReferenceStartState extends AbstractCharacterReferenceState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        if (preg_match('/[0-9]/', $character)) {
            $tokenizer->setState(new DecimalCharacterReferenceState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        } else {
            $this->flushCodePoints($tokenizer);

            $tokenizer->setState($tokenizer->getReturnState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        }
    }
}
