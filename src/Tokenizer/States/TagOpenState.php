<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\States\TagNameState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\TagToken;

class TagOpenState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            // case '!': TODO
            // case '/':
            // case '?':
            // case 'EOL':
            default:
                if (preg_match('/[a-zA-Z]/', $character)) {
                    $tagNameState = new TagNameState();
                    $tokenizer->setState($tagNameState);
                    $tokenizer->setCurrentToken(new TagToken());

                    $tagNameState->processCharacter($character, $tokenizer);
                }
        }
    }
}
