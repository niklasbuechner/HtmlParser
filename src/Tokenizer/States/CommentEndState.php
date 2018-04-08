<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CommentEndState implements State
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

            case '-':
                $tokenizer->getToken()->appendCharacterToData('-');
                break;

            case '!':
                $tokenizer->setState(new CommentEndBangState());
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $tokenizer->emitCurrentToken();
                $tokenizer->emitEofToken();
                break;

            default:
                $tokenizer->getToken()->appendCharacterToData('--');
                $tokenizer->setState(new CommentState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
