<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class NumericCharacterReferenceState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenzier)
    {
        switch ($character) {
            case 'x':
            case 'X':
                $tokenzier->appendToTemporaryBuffer('x');
                $tokenzier->setCharacterReferenceCode(0);
                $tokenzier->setState(new HexadecimalCharacterReferenceStartState());
                break;

            default:
                $tokenzier->setState(new DecimalCharacterReferenceStartState());
                $tokenzier->getState()->processCharacter($character, $tokenzier);
                break;
        }
    }
}
