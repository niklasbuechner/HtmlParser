<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CommentEndDashState implements State
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

            case Tokenizer::END_OF_FILE_CHARACTER:
                // Eof-in-comment parser error
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
