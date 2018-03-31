<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CharacterReferenceState implements State
{
    public function __construct(Tokenizer $tokenizer)
    {
        $tokenizer->appendToTemporaryBuffer('&');
    }

    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '#':
                # TODO
                break;

            default:
                if (preg_match('/[a-zA-Z0-9]/', $character)) {
                    $tokenizer->setState(new NamedCharacterReferenceState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
