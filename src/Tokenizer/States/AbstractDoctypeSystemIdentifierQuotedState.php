<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

abstract class AbstractDoctypeSystemIdentifierQuotedState extends AbstractDoctypeState
{
    /**
     * Returns the delimiter of the system identifier within the doctype tag.
     */
    abstract public function getSystemIdentifierDelimiter();

    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case $this->getSystemIdentifierDelimiter():
                $tokenizer->setState(new AfterDoctypeSystemIdentifierState());
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $this->unexpectedEndOfFileInDoctype($tokenizer);
                break;

            case '>':
                $this->unexpectedClosedDoctypeTag($tokenizer);
                break;

            default:
                $tokenizer->getToken()->appendCharacterToSystemIdentifier($character);
                break;
        }
    }
}
