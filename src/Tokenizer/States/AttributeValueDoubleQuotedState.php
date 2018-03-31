<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class AttributeValueDoubleQuotedState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            // case '"': TODO
            case '&':
                $tokenizer->setState(
                    new CharacterReferenceState($tokenizer)
                );
                $tokenizer->setReturnState($this);
                break;

            default:
                $tag = $tokenizer->getCurrentToken();
                $tag->getCurrentAttribute()->appendCharacterToAttributeValue($character);
                break;
        }
    }
}
