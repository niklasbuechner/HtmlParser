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
                $tokenizer->getCurrentToken()->appendCharacterToData('<');
                $tokenizer->setState(new CommentLessThanSignState());
                break;
            case '-':
                $tokenizer->setState(new CommentEndDashState());
                break;

            default:
                $tokenizer->getCurrentToken()->appendCharacterToData($character);
                break;
        }
    }
}
