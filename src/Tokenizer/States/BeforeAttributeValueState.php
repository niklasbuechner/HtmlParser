<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class BeforeAttributeValueState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '"':
                $tokenizer->setState(new AttributeValueDoubleQuotedState());
                break;

            case '\'':
            //TODO
                break;

            default:
                break;
        }
    }
}
