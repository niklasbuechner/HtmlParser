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

class BeforeHtmlInsertionMode implements InsertionMode
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
        } elseif ($token instanceof DoctypeToken) {
            return;
        } elseif ($token instanceof CharacterToken && preg_match('/\s/', $token->getCharacter())) {
            return;
        } elseif ($token instanceof StartTagToken && $token->getName() === 'html') {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));

            $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());
        } elseif ($token instanceof EndTagToken && !in_array($token->getName(), $this->acceptableEndTagNames)) {
            // Parse error
            return;
        } else {
            $domBuilder->insertNode($elementFactory->createElementFromTagName('html'));

            $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());
            $treeConstructor->getInsertionMode()->processToken($token, $treeConstructor, $elementFactory, $domBuilder);
        }
    }
}
