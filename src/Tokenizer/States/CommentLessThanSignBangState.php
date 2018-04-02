<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CommentLessThanSignBangState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '-':
                $tokenizer->setState(new CommentLessThanSignBangDashState);
                break;

            default:
                $tokenizer->setState(new CommentState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
