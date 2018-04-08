<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

abstract class AbstractAttributeValueState implements State
{
    /**
     * Function to return the delimiter of the value.
     */
    abstract public function getValueDelimiter();

    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case $this->getValueDelimiter():
                $tokenizer->setState(new AfterAttributeValueQuotedState());
                break;

            case '&':
                $tokenizer->setState(
                    new CharacterReferenceState($tokenizer)
                );
                $tokenizer->setReturnState($this);
                break;

            default:
                $tokenizer->getToken()->getCurrentAttribute()->appendCharacterToAttributeValue($character);
                break;
        }
    }
}
