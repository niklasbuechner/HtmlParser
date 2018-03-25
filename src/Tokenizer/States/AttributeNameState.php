<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class AttributeNameState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            // case '/':
            // case '>':
            // case '=':

            default:
                $attribute = $tokenizer->getCurrentToken()->getCurrentAttribute();
                $attribute->appendCharacterToAttributeName($character);
                break;
        }
    }
}
