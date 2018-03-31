<?php
namespace HtmlParser\Tokenizer\States;

use \Exception;
use HtmlParser\Tokenizer\Entities\EntitySearch;
use HtmlParser\Tokenizer\Tokenizer;

class NamedCharacterReferenceState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case ';':
                $tokenizer->appendToTemporaryBuffer($character);

                $this->processTemporaryBufferAsCompleteHtmlEntity($tokenizer);
                break;

            default:
                if (preg_match('/[a-zA-Z0-9]/', $character)) {
                    $tokenizer->appendToTemporaryBuffer($character);
                }
                break;
        }
    }

    /**
     * Process an html entity from the temporary buffer.
     *
     * @param Tokenizer $tokenizer
     */
    private function processTemporaryBufferAsCompleteHtmlEntity(Tokenizer $tokenizer)
    {
        try {
            $entitySearch = new EntitySearch();
            $entity = $entitySearch->getNamedCharacterEntity($tokenizer->getTemporaryBuffer());

            $returnState = $tokenizer->getReturnState();
            $tokenizer->setState($returnState);

            for ($i = 0; $i < mb_strlen($entity); $i++) {
                $returnState->processCharacter(mb_substr($entity, 0, 1), $tokenizer);
            }
        } catch (Exception $exception) {
            //TODO
            throw $exception;
        }
    }
}
