<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CommentLessThanSignBangDashDashState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '>':
            case Tokenizer::END_OF_FILE_CHARACTER:
                $tokenizer->setState(new CommentEndState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;

            default:
                // Nested comment parser error
                $tokenizer->setState(new CommentEndState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
