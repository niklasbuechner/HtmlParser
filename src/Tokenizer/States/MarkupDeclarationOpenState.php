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
            $tokenizer->setCurrentToken(new CommentToken());
        } elseif ($character === '[' && $character . $tokenizer->getNextCharacters(6) === '[CDATA[') {
            throw new Exception('CDATA elements are deprecated with HTML5 and therefore unsupported here.');
        }
    }
}
