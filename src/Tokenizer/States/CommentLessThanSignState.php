<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CommentLessThanSignState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '!':
                $tokenizer->getToken()->appendCharacterToData('!');
                $tokenizer->setState(new CommentLessThanSignBangState());
                break;

            case '<':
                $tokenizer->getToken()->appendCharacterToData('<');
                break;

            default:
                $tokenizer->setState(new CommentState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
