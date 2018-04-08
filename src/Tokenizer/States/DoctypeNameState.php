<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class DoctypeNameState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '>':
                $tokenizer->setState(new DataState());
                $tokenizer->emitCurrentToken();
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $tokenizer->getToken()->turnOnForceQuirksFlag();
                $tokenizer->emitCurrentToken();
                $tokenizer->emitEofToken();
                break;

            default:
                if (preg_match('/\s/', $character)) {
                    $tokenizer->setState(new AfterDoctypeNameState());
                } else {
                    $tokenizer->getToken()->appendCharacterToName($character);
                }
                break;
        }
    }
}
