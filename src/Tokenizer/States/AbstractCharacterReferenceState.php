<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

abstract class AbstractCharacterReferenceState implements State
{
    /**
     * Flushes all code points. (Not unicode sensitive!)
     *
     * @param Tokenizer $tokenizer
     */
    protected function flushCodePoints(Tokenizer $tokenizer)
    {
        if ($tokenizer->getReturnState() instanceof AbstractAttributeValueState) {
            $tokenizer->getToken()->getCurrentAttribute()->appendCharacterToAttributeValue($tokenizer->getTemporaryBuffer());
        } else {
            $temporaryBuffer = $tokenizer->getTemporaryBuffer();

            for ($i = 0; $i < mb_strlen($temporaryBuffer, '8bit'); $i += 1) {
                $tokenizer->emitToken(new CharacterToken($temporaryBuffer[$i]));
            }
        }
    }

    /**
     * Flushes a character from an html entity. (Unicode sensitive)
     *
     * @param Tokenizer $tokenizer
     * @param string $entity
     */
    protected function flushCharacterFromEntity(Tokenizer $tokenizer, $entity)
    {
        if ($tokenizer->getReturnState() instanceof AbstractAttributeValueState) {
            $tokenizer->getToken()->getCurrentAttribute()->appendCharacterToAttributeValue($entity);
        } else {
            $tokenizer->emitToken(new CharacterToken($entity));
        }
    }
}
