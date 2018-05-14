<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class NumericCharacterReferenceEndState extends AbstractCharacterReferenceState implements State
{
    /**
     * @inheritdoc
     */
    public function __construct(Tokenizer $tokenizer)
    {
        $entity = html_entity_decode('&#' . $tokenizer->getCharacterReferenceCode() . ';');

        if ($entity === '&#' . $tokenizer->getCharacterReferenceCode() . ';') {
            $this->flushCodePoints($tokenizer);

            // no character reference found
            return;
        }

        $tokenizer->clearTemporaryBuffer();
        $this->flushCharacterFromEntity($tokenizer, $entity);
    }

    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        $tokenizer->setState($tokenizer->getReturnState());
        $tokenizer->getState()->processCharacter($character, $tokenizer);
    }
}
