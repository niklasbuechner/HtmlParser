<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CommentStartState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '-':
                $tokenizer->setState(new CommentStartDashState());
                break;
            case '>':
                // abrupt-closing-of-empty-comment error
                $tokenizer->emitCurrentToken();
                $tokenizer->setState(new DataState());
                break;
            default:
                $tokenizer->setState(new CommentState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
