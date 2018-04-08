<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class AttributeValueUnquotedState extends AbstractAttributeValueState
{
    /**
     * @inheritdoc
     */
    public function getValueDelimiter()
    {
        return ' ';
    }

     /**
      * @inheritdoc
      */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '>':
                $tokenizer->emitCurrentToken();
                $tokenizer->setState(new DataState());
                break;

            case '"':
            case '\'':
            case '=':
            case '<':
            case '`':
                // unexpected-character-in-unquoted-attribute-value error
                // fall through

            default:
                if (preg_match('/\s/', $character)) {
                    $tokenizer->setState(new BeforeAttributeNameState());
                } else {
                    parent::processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
