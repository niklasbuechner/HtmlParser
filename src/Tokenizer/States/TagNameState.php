<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\EndOfFileToken;

class TagNameState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        $tagToken = $tokenizer->getCurrentToken();

        switch ($character) {
            case '/':
                $tokenizer->setState(new SelfClosingStartTagState());
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                // eof-in-tag error
                $tokenizer->emitToken(new EndOfFileToken());
                break;

            case '>':
                $tokenizer->setState(new DataState());
                $tokenizer->emitCurrentToken();
                break;

            default:
                if (preg_match('/\s/', $character)) {
                    $tokenizer->setState(new BeforeAttributeNameState());
                } else {
                    $tagToken->appendCharacterToName($character);
                }
                break;
        }
    }
}
