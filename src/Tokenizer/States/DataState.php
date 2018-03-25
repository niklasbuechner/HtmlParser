<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class DataState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            // case "&": TODO
            // case EOF
            case "<":
                $tokenizer->setState(new TagOpenState());
                break;

            default:
                //TOOD
                break;
        }
    }
}
