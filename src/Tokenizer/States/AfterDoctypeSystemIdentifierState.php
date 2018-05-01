<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class AfterDoctypeSystemIdentifierState extends AbstractDoctypeState
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
                if (!preg_match('/\s/', $character)) {
                    $tokenizer->setState(new BogusDoctypeState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
