<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\EndOfFileToken;

class SelfClosingStartTagState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '>':
                $tokenizer->getCurrentToken()->setAsSelfClosing();
                $tokenizer->emitCurrentToken();
                $tokenizer->setState(new DataState());
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                // eof-in-tag error
                $tokenizer->emitToken(new EndOfFileToken());
                break;

            default:
                // unexpected-solidus-in-tag error
                $tokenizer->setState(new BeforeAttributeNameState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
