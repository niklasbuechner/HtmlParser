<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class BogusDoctypeState extends AbstractDoctypeState
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '>':
                $tokenizer->setState(new DataState);
                $tokenizer->emitCurrentToken();
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                $this->unexpectedEndOfFileInDoctype($tokenizer);
                break;

            default:
                break;
        }
    }
}
