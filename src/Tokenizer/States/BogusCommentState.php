<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

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
                $tokenizer->emitEofToken();
                break;

            default:
                $tokenizer->getToken()->appendCharacterToData($character);
                break;
        }
    }
}
