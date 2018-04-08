<?php
namespace HtmlParser\Tokenizer\States;

use \Exception;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CommentToken;

class MarkupDeclarationOpenState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        if ($character === '-' && $character . $tokenizer->getNextCharacters(1) === '--') {
            $tokenizer->consumeNextCharacters(1);
            $tokenizer->setState(new CommentStartState());
            $tokenizer->setToken(new CommentToken());
        } elseif (mb_strtolower($character . $tokenizer->getNextCharacters(6)) === 'doctype') {
            $tokenizer->consumeNextCharacters(6);
            $tokenizer->setState(new DoctypeState());
        }
    }
}
