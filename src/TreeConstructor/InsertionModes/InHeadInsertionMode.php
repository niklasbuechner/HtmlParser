<?php
namespace HtmlParser\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\States\RcdataState;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\Tokenizer\Tokens\Token;
use HtmlParser\TreeConstructor\TreeConstructor;

class InHeadInsertionMode implements InsertionMode
{
    /**
     * List of all self closing tag which belong into the head.
     */
    private static $selfClosingHeadTags;

    public function __construct()
    {
        self::$selfClosingHeadTags = [
            'meta',
            'link',
            'base',
            'basefont',
            'bgsound',
        ];
    }

    /**
     * @inheritdoc
     */
    public function processToken(Token $token, TreeConstructor $treeConstructor)
    {
        // TODO Character
        // TODO html
        if ($token instanceof StartTagToken) {
            if (in_array($token->getName(), self::$selfClosingHeadTags)) {
                $treeConstructor->insertNode($treeConstructor->createElementFromToken($token));
                $treeConstructor->popLastElementOfStackOfOpenElements();

                return;
            }

            if ($token->getName() === 'title') {
                $treeConstructor->insertNode($treeConstructor->createElementFromToken($token));

                $treeConstructor->setOriginalInsertionMode($this);
                $treeConstructor->setInsertionMode(new TextInsertionMode());
                $treeConstructor->getTokenizer()->setState(new RcdataState());

                return;
            }
        }

        if ($token instanceof DoctypeToken) {
            return;
        }

        if ($token instanceof CommentToken) {
            $treeConstructor->addComment($token);

            return;
        }

        $treeConstructor->popLastElementOfStackOfOpenElements();
        $treeConstructor->setInsertionMode(new AfterHeadInsertionMode());
    }
}
