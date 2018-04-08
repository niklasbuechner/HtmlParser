<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\EndTagToken;

class EndTagOpenState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '>':
                # code...
                break;

            default:
                if (preg_match('/[a-zA-Z]/', $character)) {
                    $tokenizer->setToken(new EndTagToken());
                    $tokenizer->setState(new TagNameState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
