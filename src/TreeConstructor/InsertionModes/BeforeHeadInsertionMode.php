<?php
namespace HtmlParser\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;

class BeforeHeadInsertionMode implements InsertionMode
{
    /**
     * @var string[]
     */
    private $acceptableEndTagNames;

    public function __construct()
    {
        $this->acceptableEndTagNames = [
            'head',
            'body',
            'html',
            'br',
        ];
    }

    /**
     * @inheritdoc
     */
    public function processToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        if ($token instanceof CommentToken) {
            $domBuilder->addComment($elementFactory->createCommentFromToken($token));

            return;
        } elseif ($token instanceof CharacterToken && preg_match('/\s/', $token->getCharacter())) {
            return;
        } elseif ($token instanceof DoctypeToken) {
            // TODO parser error
            return;
        } elseif ($token instanceof StartTagToken && $token->getName() === 'head') {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
            $treeConstructor->setInsertionMode(new InHeadInsertionMode());
        } elseif ($token instanceof EndTagToken && !in_array($token->getName(), $this->acceptableEndTagNames)) {
            return;
        } else {
            $domBuilder->insertNode($elementFactory->createElementFromTagName('head'));
            $treeConstructor->setInsertionMode(new InHeadInsertionMode());
            $treeConstructor->getInsertionMode()->processToken($token, $treeConstructor, $elementFactory, $domBuilder);
        }
    }
}
