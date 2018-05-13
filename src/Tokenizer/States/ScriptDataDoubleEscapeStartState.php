<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataDoubleEscapeStartState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        if (preg_match('/\s/', $character) || $character === '/' || $character === '>') {
            if ($tokenizer->getTemporaryBuffer() === 'script') {
                $tokenizer->setState(new ScriptDataDoubleEscapedState());
            } else {
                $tokenizer->setState(new ScriptDataEscapedState());
            }

            $tokenizer->emitToken(new CharacterToken($character));
        } elseif (preg_match('/[a-zA-Z]/', $character)) {
            $tokenizer->appendToTemporaryBuffer(mb_strtolower($character));
            $tokenizer->emitToken(new CharacterToken($character));
        } else {
            $tokenizer->setState(new ScriptDataEscapedState());
            $tokenizer->getState()->processCharacter($character, $tokenizer);
        }
    }
}
