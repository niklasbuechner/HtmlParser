<?php
namespace HtmlParser\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;

class InBodyInsertionMode implements InsertionMode
{
    /**
     * @var string[]
     */
    private $tagsToBeProcessedByHeadRules;

    public function __construct()
    {
        $this->tagsToBeProcessedByHeadRules = [
            'base',
            'basefont',
            'bgsound',
            'link',
            'meta',
            'noframeset',
            'script',
            'style',
            'template',
            'title',
        ];
    }

    /**
     * @inheritdoc
     */
    public function processToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        if ($token instanceof CharacterToken) {
            $domBuilder->insertCharacter($token->getCharacter());
            $domBuilder->setFramesetOkayFlag(false);
        } elseif ($token instanceof StartTagToken) {
            $this->processStartTagToken($token, $treeConstructor, $elementFactory, $domBuilder);
        } elseif ($token instanceof CommentToken) {
            $domBuilder->addComment($elementFactory->createCommentFromToken($token));
        } elseif ($token instanceof DoctypeToken) {
            return;
        }
    }

    /**
     * Processes a start tag token.
     *
     * @param Token $token
     * @param TreeConstructor $treeConstructor
     * @param ElementFactory $elementFactory
     * @param DomBuilder $domBuilder
     */
    public function processStartTagToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        if ($token->getName() === 'html') {
            if ($domBuilder->containsStackOfOpenElements('template')) {
                return;
            }

            $domBuilder->transferAttributes($token, $domBuilder->getCurrentNode());
        } elseif (in_array($token->getName(), $this->tagsToBeProcessedByHeadRules)) {
            $inHeadInsertionMode = new InHeadInsertionMode();
            $inHeadInsertionMode->processToken($token, $treeConstructor, $elementFactory, $domBuilder);
        } elseif ($token->getName() === 'body') {
            $domBuilder->transferAttributes($token, $domBuilder->getStackOfOpenElements()[2]);
        }
    }
}
