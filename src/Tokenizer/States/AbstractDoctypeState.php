<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

abstract class AbstractDoctypeState implements State
{
    /**
     * @inheritdoc
     */
    abstract public function processCharacter($character, Tokenizer $tokenizer);

    /**
     * Handles an unexpected end of file within the doctype tag.
     *
     * @param Tokenizer $tokenizer
     */
    protected function unexpectedEndOfFileInDoctype(Tokenizer $tokenizer)
    {
        $tokenizer->getToken()->turnOnQuirksMode();
        $tokenizer->emitCurrentToken();
        $tokenizer->emitEofToken();
    }

    /**
     * Handles an unexpected end of the doctype tag.
     *
     * @param Tokenizer $tokenizer
     */
    protected function unexpectedClosedDoctypeTag(Tokenizer $tokenizer)
    {
        $tokenizer->getToken()->turnOnQuirksMode();
        $tokenizer->setState(new DataState());
        $tokenizer->emitCurrentToken();
    }
}
