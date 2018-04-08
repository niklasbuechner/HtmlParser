<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;

class DoctypeState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '>':
                $tokenizer->setState(new BeforeDoctypeNameState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $tokenizer->setToken(new DoctypeToken());
                $tokenizer->getToken()->turnOnForceQuirksFlag();
                $tokenizer->emitCurrentToken();
                $tokenizer->emitEofToken();
                break;

            default:
                if (preg_match('/\s/', $character)) {
                    $tokenizer->setState(new BeforeDoctypeNameState());
                } else {
                    // Missing-whitespace-before-doctype-name error
                    $tokenizer->setState(new BeforeDoctypeNameState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
