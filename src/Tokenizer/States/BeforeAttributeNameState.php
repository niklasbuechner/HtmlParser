<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Structs\AttributeStruct;

class BeforeAttributeNameState implements State
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
            //     # code...
            //     break;

            default:
                if (!preg_match('/\s/', $character)) {
                    $attributeNameState = new AttributeNameState();
                    $tokenizer->setState($attributeNameState);
                    $tokenizer->getCurrentToken()->setCurrentAttribute(new AttributeStruct);

                    $attributeNameState->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
