<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;

class BeforeDoctypeNameState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '>':
                $tokenizer->setToken(new DoctypeToken());
                $tokenizer->getToken()->turnOnForceQuirksFlag();
                $tokenizer->setState(new DataState());
                $tokenizer->emitCurrentToken();
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $tokenizer->setToken(new DoctypeToken());
                $tokenizer->getToken()->turnOnForceQuirksFlag();
                $tokenizer->emitCurrentToken();
                $tokenizer->emitEofToken();
                break;

            default:
                if (!preg_match('/\s/', $character)) {
                    $tokenizer->setToken(new DoctypeToken());
                    $tokenizer->getToken()->appendCharacterToName($character);
                    $tokenizer->setState(new DoctypeNameState());
                }
                break;
        }
    }
}
