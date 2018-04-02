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
    }
}
