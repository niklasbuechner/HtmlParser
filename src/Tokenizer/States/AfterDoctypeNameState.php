<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class AfterDoctypeNameState extends AbstractDoctypeState
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '>':
                $tokenizer->setState(new DataState());
                $tokenizer->emitCurrentToken();
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $this->unexpectedEndOfFileInDoctype($tokenizer);
                break;

            default:
                if (mb_strtolower($character . $tokenizer->getNextCharacters(5)) === 'public') {
                    $tokenizer->consumeNextCharacters(5);
                    $tokenizer->setState(new AfterDoctypePublicKeywordState());
                } elseif (mb_strtolower($character . $tokenizer->getNextCharacters(5)) === 'system') {
                    $tokenizer->consumeNextCharacters(5);
                    $tokenizer->setState(new AfterDoctypeSystemKeywordState());
                } else {
                    $tokenizer->getToken()->turnOnQuirksMode();
                    $tokenizer->setState(new BogusDoctypeState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
