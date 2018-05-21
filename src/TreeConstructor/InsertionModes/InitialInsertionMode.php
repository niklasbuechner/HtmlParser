<?php
namespace HtmlParser\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\Token;
use HtmlParser\TreeConstructor\Nodes\DoctypeNode;
use HtmlParser\TreeConstructor\TreeConstructor;

class InitialInsertionMode implements InsertionMode
{
    /**
     * @inheritdoc
     */
    public function processToken(Token $token, TreeConstructor $treeConstructor)
    {
        if ($token instanceof CommentToken) {
            $treeConstructor->addComment($token);

            return;
        }

        if ($token instanceof DoctypeToken) {
            $treeConstructor->getDocumentNode()->setDoctypeAttribute(
                DoctypeNode::fromToken($token)
            );
            $treeConstructor->setInsertionMode(new BeforeHtmlInsertionMode());

            return;
        }

        if ($token instanceof CharacterToken && preg_match('/\s/', $token->getCharacter())) {
            return;
        }

        // Parser error
        $treeConstructor->setInsertionMode(new BeforeHtmlInsertionMode());
        $treeConstructor->getInsertionMode()->processToken($token, $treeConstructor);
    }
}
