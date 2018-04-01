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
        $tagToken = $tokenizer->getCurrentToken();

        switch ($character) {
            // case EOL TODO
            // case '/':
            //     break;

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
