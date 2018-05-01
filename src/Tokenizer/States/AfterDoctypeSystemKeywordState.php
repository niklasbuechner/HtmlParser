<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class AfterDoctypeSystemKeywordState extends AbstractDoctypeState
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '"':
                $tokenizer->setState(new DoctypeSystemIdentifierDoubleQuotedState());
                break;

            case '\'':
                $tokenizer->setState(new DoctypeSystemIdentifierSingleQuotedState());
                break;

            case '>':
                $this->unexpectedClosedDoctypeTag($tokenizer);
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $this->unexpectedEndOfFileInDoctype($tokenizer);
                break;

            default:
                if (preg_match('/\s/', $character)) {
                    $tokenizer->setState(new BeforeDoctypeSystemIdentifierState());
                } else {
                    $tokenizer->getToken()->turnOnQuirksMode();
                    $tokenizer->setState(new BogusDoctypeState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
