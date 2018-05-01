<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class AfterDoctypePublicKeywordState extends AbstractDoctypeState
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '"':
                // missing-whitespace-after-doctype-public-keyword error
                $tokenizer->setState(new DoctypePublicIdentifierDoubleQuotedState());
                break;

            case '\'':
                // missing-whitespace-after-doctype-public-keyword error
                $tokenizer->setState(new DoctypePublicIdentifierSingleQuotedState());
                break;

            case '>':
                $this->unexpectedClosedDoctypeTag($tokenizer);
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $this->unexpectedEndOfFileInDoctype($tokenizer);
                break;

            default:
                if (preg_match('/\s/', $character)) {
                    $tokenizer->setState(new BeforeDoctypePublicIdentifierState());
                } else {
                    $tokenizer->getToken()->turnOnQuirksMode();
                    $tokenizer->setState(new BogusDoctypeState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
