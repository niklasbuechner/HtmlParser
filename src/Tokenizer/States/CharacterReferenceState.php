<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CharacterReferenceState extends AbstractCharacterReferenceState implements State
{
    public function __construct(Tokenizer $tokenizer)
    {
        $tokenizer->clearTemporaryBuffer();
        $tokenizer->appendToTemporaryBuffer('&');
    }

    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '#':
                $tokenizer->appendToTemporaryBuffer('#');
                $tokenizer->setState(new NumericCharacterReferenceState());
                break;

            default:
                if (preg_match('/[a-zA-Z0-9]/', $character)) {
                    $tokenizer->setState(new NamedCharacterReferenceState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                } else {
                    $this->flushCodePoints($tokenizer);

                    $tokenizer->setState($tokenizer->getReturnState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
