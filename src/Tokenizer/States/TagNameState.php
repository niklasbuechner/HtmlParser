<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class TagNameState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        $tagToken = $tokenizer->getToken();

        switch ($character) {
            case '/':
                $tokenizer->setState(new SelfClosingStartTagState());
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                // eof-in-tag error
                $tokenizer->emitEofToken();
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
