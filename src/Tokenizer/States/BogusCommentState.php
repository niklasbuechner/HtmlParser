<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\EndOfFileToken;

class BogusCommentState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '>':
                $tokenizer->emitCurrentToken();
                $tokenizer->setState(new DataState());
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $tokenizer->emitCurrentToken();
                $tokenizer->emitToken(new EndOfFileToken());
                break;

            default:
                $tokenizer->getCurrentToken()->appendCharacterToData($character);
                break;
        }
    }
}
