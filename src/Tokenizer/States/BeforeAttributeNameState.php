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
            case '>':
            case '/':
            case Tokenizer::END_OF_FILE_CHARACTER:
                $tokenizer->setState(new AfterAttributeNameState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;

            case '=':
                // unexpected-equals-sign-before-attribute-name
                $tokenizer->getToken()->addAttribute(new AttributeStruct());
                $tokenizer->setState(new AttributeNameState());
                break;

            default:
                if (!preg_match('/\s/', $character)) {
                    $tokenizer->setState(new AttributeNameState());
                    $tokenizer->getToken()->addAttribute(new AttributeStruct());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
