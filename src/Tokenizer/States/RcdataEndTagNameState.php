<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;

class RcdataEndTagNameState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                // TODO check if its the appropriate tag name
                $tokenizer->setState(new SelfClosingStartTagState());
                break;

            case '>':
                // TODO check if its the appropriate tag name
                $tokenizer->emitCurrentToken();
                $tokenizer->setState(new DataState());
                break;

            default:
                if (preg_match('/\s/', $character)) {
                    // TODO check if its the appropriate tag name
                    $tokenizer->setState(new BeforeAttributeNameState());
                } elseif (preg_match('/[a-zA-Z]/', $character)) {
                    $tokenizer->appendToTemporaryBuffer($character);
                } else {
                    $tokenizer->emitToken(new CharacterToken('<'));
                    $tokenizer->emitToken(new CharacterToken('/'));

                    $temporaryBuffer = $tokenizer->getTemporaryBuffer();

                    for ($i = 0; $i < mb_strlen($temporaryBuffer, '8bit'); $i += 1) {
                        $tokenizer->emitToken(new CharacterToken($temporaryBuffer[$i]));
                    }

                    $tokenizer->setState(new RcdataState());
                    $tokenizer->getState()->processCharacter($character, $tokenizer);
                }
                break;
        }
    }
}
