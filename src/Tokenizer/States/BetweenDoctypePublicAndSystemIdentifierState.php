<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class BetweenDoctypePublicAndSystemIdentifierState extends AbstractDoctypeState
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '"':
                $tokenizer->setState(new DoctypeSystemIdentifierDoubleQuotedState());
                break;

            case '\'':
                $tokenizer->setState(new DoctypeSystemIdentifierSingleQuotedState());
                break;

            case '>':
                $tokenizer->setState(new DataState());
                $tokenizer->emitCurrentToken();
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $tokenizer->emitCurrentToken();
                $tokenizer->emitEofToken();
                break;

            default:
                if (!preg_match('/\s/', $character)) {
                    $tokenizer->getToken()->turnOnQuirksMode();
                    $tokenizer->setState(new BogusDoctypeState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
