<?php
namespace HtmlParser\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
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
    public function processToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        // TODO Character
        // TODO html
        if ($token instanceof StartTagToken) {
            $this->processStartTagToken($token, $treeConstructor, $elementFactory, $domBuilder);
        } elseif ($token instanceof DoctypeToken) {
            return;
        } elseif ($token instanceof CommentToken) {
            $domBuilder->addComment($elementFactory->createCommentFromToken($token));
        } else {
            $this->processUnexpectedToken($token, $treeConstructor, $elementFactory, $domBuilder);
        }
    }

    /**
     * Processes a start tag token.
     *
     * @param StartTagToken $token
     * @param TreeConstructor $treeConstructor
     * @param ElementFactory $elementFactory
     * @param DomBuilder $domBuilder
     */
    private function processStartTagToken(StartTagToken $token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        if (in_array($token->getName(), self::$selfClosingHeadTags)) {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
            $domBuilder->popLastElementOfStackOfOpenElements();
        } elseif ($token->getName() === 'title') {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));

            $treeConstructor->setOriginalInsertionMode($this);
            $treeConstructor->setInsertionMode(new TextInsertionMode());
            $treeConstructor->getTokenizer()->switchToRcdataTokenization();
        } else {
            $this->processUnexpectedToken($token, $treeConstructor, $elementFactory, $domBuilder);
        }
    }

    /**
     * Processes an unexpected tag token.
     *
     * @param $token
     * @param TreeConstructor $treeConstructor
     * @param ElementFactory $elementFactory
     * @param DomBuilder $domBuilder
     */
    private function processUnexpectedToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        $domBuilder->popLastElementOfStackOfOpenElements();
        $treeConstructor->setInsertionMode(new AfterHeadInsertionMode());
    }
}
