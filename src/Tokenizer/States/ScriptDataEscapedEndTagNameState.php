<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataEscapedEndTagNameState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                // TODO test for appropriate tag
                $tokenizer->setState(new SelfClosingStartTagState());
                break;

            case '>':
                // TODO test for appropriate tag
                $tokenizer->setState(new DataState());
                $tokenizer->emitCurrentToken();
                break;

            default:
                if (preg_match('/\s/', $character)) {
                    // TODO test for appropriate tag
                    $tokenizer->setState(new BeforeAttributeNameState());
                } elseif (preg_match('/[a-zA-Z]/', $character)) {
                    $tokenizer->appendToTemporaryBuffer($character);
                    $tokenizer->getToken()->appendCharacterToName($character);
                } else {
                    $tokenizer->emitToken(new CharacterToken('<'));
                    $tokenizer->emitToken(new CharacterToken('/'));

                    $temporaryBuffer = $tokenizer->getTemporaryBuffer();

                    for ($i = 0; $i < mb_strlen($temporaryBuffer, '8bit'); $i += 1) {
                        $tokenizer->emitToken(new CharacterToken($temporaryBuffer[$i]));
                    }

                    $tokenizer->setState(new ScriptDataEscapedState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
