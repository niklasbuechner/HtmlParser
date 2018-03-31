<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class CharacterReferenceState implements State
{
    /**
     * @var State
     */
    private $returnState;

    public function __construct(State $returnState)
    {
        $this->returnState = $returnState;
    }

    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '#':
                # code...
                break;

            default:
                # code...
                break;
        }
    }
}
