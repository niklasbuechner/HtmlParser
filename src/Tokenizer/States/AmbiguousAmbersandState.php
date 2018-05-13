<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class AmbiguousAmbersandState extends AbstractCharacterReferenceState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        if (preg_match('/[a-zA-Z0-9]/', $character)) {
            $tokenizer->clearTemporaryBuffer();
            $tokenizer->appendToTemporaryBuffer($character);
            $this->flushCodePoints($tokenizer);

            return;
        }

        $tokenizer->setState($tokenizer->getReturnState());
        $tokenizer->getState()->processCharacter($character, $tokenizer);
    }
}
