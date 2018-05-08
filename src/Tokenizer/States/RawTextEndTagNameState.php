<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class RawTextEndTagNameState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                // TODO check if its an aprropriate token.
                $tokenizer->setState(new SelfClosingStartTagState());
                break;

            case '>':
                // TODO check if its an aprropriate token.
                $tokenizer->emitCurrentToken();
                $tokenizer->setState(new DataState());
                break;

            default:
                if (preg_match('/[a-zA-Z]/', $character)) {
                    $tokenizer->getToken()->appendCharacterToName($character);
                    $tokenizer->appendToTemporaryBuffer($character);
                } elseif (preg_match('/\s/', $character)) {
                    // TODO check if its an aprropriate token.
                    $tokenizer->setState(new BeforeAttributeNameState());
                } else {
                    $tokenizer->emitToken(new CharacterToken('<'));
                    $tokenizer->emitToken(new CharacterToken('/'));

                    $temporaryBuffer = $tokenizer->getTemporaryBuffer();

                    for ($i = 0; $i < mb_strlen($temporaryBuffer, '8bit'); $i += 1) {
                        $tokenizer->emitToken(new CharacterToken($temporaryBuffer[$i]));
                    }

                    $tokenizer->setState(new RawTextState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
