<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class DecimalCharacterReferenceState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case ';':
                $tokenizer->setState(new NumericCharacterReferenceEndState($tokenizer));
                break;

            default:
                if (preg_match('/[0-9]/', $character)) {
                    $tokenizer->setCharacterReferenceCode(
                        $tokenizer->getCharacterReferenceCode() * 10 + $character
                    );
                } else {
                    $tokenizer->setState(new NumericCharacterReferenceEndState($tokenizer));
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
