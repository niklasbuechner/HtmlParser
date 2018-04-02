<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\StartTagToken;

class TagOpenState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case '/':
                $tokenizer->setState(new EndTagOpenState());
                break;
            case '!':
                $tokenizer->setState(new MarkupDeclarationOpenState());
                break;
            // case '?': TODO
            // case 'EOL':
            default:
                if (preg_match('/[a-zA-Z]/', $character)) {
                    $tagNameState = new TagNameState();
                    $tokenizer->setState($tagNameState);
                    $tokenizer->setCurrentToken(new StartTagToken());

                    $tagNameState->processCharacter($character, $tokenizer);
                }
                break;
            // TODO invalid first character error
        }
    }
}
