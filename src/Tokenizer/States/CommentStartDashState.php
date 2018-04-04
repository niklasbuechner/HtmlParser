<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CommentStartDashState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '-':
                $tokenizer->setState(new CommentEndState());
                break;

            case '>':
                // Abrupt-closing-of-empty-comment error
                $tokenizer->setState(new DataState());
                $tokenizer->emitCurrentToken();
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                // eof-in-comment error
                $tokenizer->emitCurrentToken();
                $tokenizer->emitEofToken();
                break;

            default:
                $tokenizer->getCurrentToken()->appendCharacterToData('-');
                $tokenizer->setState(new CommentState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
