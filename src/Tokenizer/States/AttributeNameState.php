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
            case '/':
            case '>':
                $tokenizer->setState(new AfterAttributeNameState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;

            case '=':
                $tokenizer->setState(new BeforeAttributeValueState());
                break;

            case '\'':
            case '"':
            case '<':
                // unexpected-character-in-attribute-name error
                // fall through
            default:
                if (preg_match('/\s/', $character)) {
                    $tokenizer->setState(new AfterAttributeNameState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                } else {
                    $attribute = $tokenizer->getToken()->getCurrentAttribute();
                    $attribute->appendCharacterToAttributeName($character);
                }
                break;
        }
    }
}
