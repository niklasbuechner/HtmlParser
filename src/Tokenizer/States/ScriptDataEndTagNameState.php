<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class ScriptDataEndTagNameState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                // TODO check for appropriate end tag
                $tokenizer->setState(new SelfClosingStartTagState());
                break;

            case '>':
                // TODO check for appropriate end tag
                $tokenizer->emitCurrentToken();
                $tokenizer->setState(new DataState());
                break;

            default:
                if (preg_match('/[a-zA-Z]/', $character)) {
                    $tokenizer->getToken()->appendCharacterToName($character);
                    $tokenizer->appendToTemporaryBuffer($character);
                } elseif (preg_match('/\s/', $character)) {
                    $tokenizer->setState(new BeforeAttributeNameState());
                } else {
                    $tokenizer->emitToken(new CharacterToken('<'));
                    $tokenizer->emitToken(new CharacterToken('/'));

                    $temporaryBuffer = $tokenizer->getTemporaryBuffer();

                    for ($i = 0; $i < mb_strlen($temporaryBuffer, '8bit'); $i += 1) {
                        $tokenizer->emitToken(new CharacterToken($temporaryBuffer[$i]));
                    }

                    $tokenizer->setState(new ScriptDataState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
