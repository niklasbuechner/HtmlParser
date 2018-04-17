<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class BeforeDoctypePublicIdentifierState extends AbstractDoctypeState
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '"':
                $tokenizer->getToken()->initPublicIdentifier();
                $tokenizer->setState(new DoctypePublicIdentifierDoubleQuotedState());
                break;

            case '\'':
                $tokenizer->getToken()->initPublicIdentifier();
                $tokenizer->setState(new DoctypePublicIdentifierSingleQuotedState());
                break;

            case '>':
                $this->unexpectedClosedDoctypeTag($tokenizer);
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $this->unexpectedEndOfFileInDoctype($tokenizer);
                break;

            default:
                # code...
                break;
        }
    }
}