<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokenizer;

class AfterAttributeNameState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                $tokenizer->setState(new SelfClosingStartTagState());
                break;

            case '=':
                $tokenizer->setState(new BeforeAttributeValueState());
                break;

            case '>':
                $tokenizer->emitCurrentToken();
                $tokenizer->setState(new DataState());
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                // eof-in-tag error
                $tokenizer->emitEofToken();
                break;

            default:
                if (!preg_match('/\s/', $character)) {
                    $tokenizer->getToken()->addAttribute(new AttributeStruct());
                    $tokenizer->setState(new AttributeNameState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
