<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

abstract class AbstractDoctypePublicIdentifierQuotedState extends AbstractDoctypeState
{
    /**
     * Returns the character ending the public identifier.
     *
     * @return string
     */
    abstract public function getPublicIdentifierDelimiter();

    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '"':
                $tokenizer->setState(new AfterDoctypePublicIdentifierState());
                break;

            case '>':
                $this->unexpectedClosedDoctypeTag($tokenizer);
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $this->unexpectedEndOfFileInDoctype($tokenizer);
                break;

            default:
                $tokenizer->getToken()->appendCharacterToPublicIdentifier($character);
                break;
        }
    }
}
