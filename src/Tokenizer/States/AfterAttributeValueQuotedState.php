<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class AfterAttributeValueQuotedState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                break;

            case '>':
                $tokenizer->setState(new DataState());
                $tokenizer->emitCurrentToken();
                break;

            default:
                if (preg_match('/\s/', $character)) {
                    $tokenizer->setState(new BeforeAttributeNameState());
                }
                break;
        }
    }
}
