<?php
namespace HtmlParser\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\Token;
use HtmlParser\TreeConstructor\Nodes\DoctypeNode;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;

class InitialInsertionMode implements InsertionMode
{
    /**
     * @inheritdoc
     */
    public function processToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        if ($token instanceof CommentToken) {
            $domBuilder->addComment($elementFactory->createCommentNodeFromToken($token));
        } elseif ($token instanceof DoctypeToken) {
            $domBuilder->getDocumentNode()->setDoctypeAttribute(
                $elementFactory->createDoctypeFromToken($token)
            );
            $treeConstructor->setInsertionMode(new BeforeHtmlInsertionMode());
        } elseif ($token instanceof CharacterToken && preg_match('/\s/', $token->getCharacter())) {
            return;
        } else {
            // Parser error
            $treeConstructor->setInsertionMode(new BeforeHtmlInsertionMode());
            $treeConstructor->getInsertionMode()->processToken($token, $treeConstructor, $elementFactory, $domBuilder);
        }
    }
}
