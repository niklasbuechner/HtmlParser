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
                $tokenizer->setState(new AttributeValueSingleQuotedState());
                break;

            default:
                if (!preg_match('/\s/', $character)) {
                    $tokenizer->setState(new AttributeValueUnquotedState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
