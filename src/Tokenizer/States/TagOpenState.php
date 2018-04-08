<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
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

            case '?':
                // unexpected-question-mark-instead-of-tag-name error
                $tokenizer->setToken(new CommentToken());
                $tokenizer->setState(new BogusCommentState());
                break;

            case Tokenizer::END_OF_FILE_CHARACTER:
                // eof-before-tag-name error
                $tokenizer->emitToken(new CharacterToken('<'));
                $tokenizer->emitEofToken();
                break;

            default:
                if (preg_match('/[a-zA-Z]/', $character)) {
                    $tagNameState = new TagNameState();
                    $tokenizer->setState($tagNameState);
                    $tokenizer->setToken(new StartTagToken());

                    $tagNameState->processCharacter($character, $tokenizer);
                } else {
                    $tokenizer->emitToken(new CharacterToken('<'));
                    $tokenizer->setState(new DataState());
                }
                break;
        }
    }
}
