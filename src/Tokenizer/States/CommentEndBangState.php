<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CommentEndBangState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '-':
                $tokenizer->getCurrentToken()->appendCharacterToData('--!');
                $tokenizer->setState(new CommentEndDashState());
                break;

            case '>':
                // incorrectly-closed-comment error TODO
                $tokenizer->setState(new DataState);
                $tokenizer->emitCurrentToken();
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                // eof-in-comment error TODO
                $tokenizer->emitCurrentToken();
                $tokenizer->emitEofToken();
                break;

            default:
                $tokenizer->getCurrentToken()->appendCharacterToData('--!');
                $tokenizer->setState(new CommentState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
