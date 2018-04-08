<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class SelfClosingStartTagState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '>':
                $tokenizer->getToken()->setAsSelfClosing();
                $tokenizer->emitCurrentToken();
                $tokenizer->setState(new DataState());
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                // eof-in-tag error
                $tokenizer->emitEofToken();
                break;

            default:
                // unexpected-solidus-in-tag error
                $tokenizer->setState(new BeforeAttributeNameState());
                $tokenizer->getState()->processCharacter($character, $tokenizer);
                break;
        }
    }
}
