<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CommentLessThanSignBangDashState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '-':
                $tokenizer->setState(new CommentLessThanSignBangDashDashState());
                break;

            default:
                $tokenizer->setState(new CommentEndDashState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
