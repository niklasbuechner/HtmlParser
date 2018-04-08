<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CommentState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '<':
                $tokenizer->getToken()->appendCharacterToData('<');
                $tokenizer->setState(new CommentLessThanSignState());
                break;

            case '-':
                $tokenizer->setState(new CommentEndDashState());
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
