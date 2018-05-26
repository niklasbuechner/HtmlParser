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
            $treeConstructor->addComment($token);

            return;
        }

        if ($token instanceof DoctypeToken) {
            return;
        }

        if ($token instanceof CharacterToken && preg_match('/\s/', $token->getCharacter())) {
            return;
        }

        if ($token instanceof StartTagToken && $token->getName() === 'html') {
            $htmlElement = $treeConstructor->createElementFromToken($token);
            $treeConstructor->getDocumentNode()->appendChild($htmlElement);
            $treeConstructor->addElementToStackOfOpenElements($htmlElement);

            $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());

            return;
        }

        if ($token instanceof EndTagToken && !in_array($token->getName(), $this->acceptableEndTagNames)) {
            // Parse error
            return;
        }

        $domBuilder->insertNode($elementFactory->createElementFromTagName('html'));

        // $treeConstructor->setInsertionMode(new BeforeHeadInsertionMode());
        // $treeConstructor->getInsertionMode()->processToken($token, $treeConstructor);
    }
}
