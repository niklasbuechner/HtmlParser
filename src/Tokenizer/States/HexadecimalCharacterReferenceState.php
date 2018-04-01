<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class HexadecimalCharacterReferenceState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case ';':
                $tokenizer->setState(new NumericCharacterReferenceEndState());
                $tokenizer->getState()->processCharacter(';', $tokenizer);
                break;

            default:
                $asciiValue = ord($character);

                if (preg_match('/[0-9]/', $character)) {
                    $tokenizer->setCharacterReferenceCode(
                        $tokenizer->getCharacterReferenceCode() * 16 + ($asciiValue - 48)
                    );

                    return;
                }

                $character = mb_strtolower($character);
                $tokenizer->setCharacterReferenceCode(
                    $tokenizer->getCharacterReferenceCode() * 16 + ($asciiValue - 55)
                );
                break;
        }
    }
}
