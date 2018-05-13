<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataEscapedLessThanSignState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                $tokenizer->clearTemporaryBuffer();
                $tokenizer->setState(new ScriptDataEscapedEndTagOpenState());
                break;

            default:
                if (preg_match('/[a-zA-Z]/', $character)) {
                    $tokenizer->clearTemporaryBuffer();
                    $tokenizer->emitToken(new CharacterToken('<'));
                    $tokenizer->setState(new ScriptDataDoubleEscapeStartState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                } else {
                    $tokenizer->emitToken(new CharacterToken('<'));
                    $tokenizer->setState(new ScriptDataEscapedState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
