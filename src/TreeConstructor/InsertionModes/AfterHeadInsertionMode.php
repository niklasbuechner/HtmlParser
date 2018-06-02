<?php
namespace HtmlParser\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\CharacterToken;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;

class AfterHeadInsertionMode implements InsertionMode
{
    /**
     * End tags which should be processed as unexpected tokens and not be ignored.
     */
    private $endTagsToNotBeIgnored;

    /**
     * @var string[]
     */
    private $tagsToBeProcessInHead;

    public function __construct()
    {
        $this->endTagsToNotBeIgnored = [
            'body',
            'html',
            'br',
        ];

        $this->tagsToBeProcessInHead = [
            'base',
            'basefont',
            'bgsound',
            'link',
            'meta',
            'noframes',
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
        } elseif ($token instanceof CommentToken) {
            $domBuilder->addComment($elementFactory->createCommentFromToken($token));
        } elseif ($token instanceof StartTagToken) {
            $this->processStartTagToken($token, $treeConstructor, $elementFactory, $domBuilder);
        } elseif ($token instanceof DoctypeToken) {
            return;
        } else {
            $this->processUnexpectedToken($token, $treeConstructor, $elementFactory, $domBuilder);
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
    private function processStartTagToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        if ($token->getName() === 'body') {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
            $domBuilder->setFramesetOkayFlag(false);
            $treeConstructor->setInsertionMode(new InBodyInsertionMode());
        } elseif ($token->getName() === 'frameset') {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
            $treeConstructor->setInsertionMode(new InFramesetInsertionMode());
        } elseif (in_array($token->getName(), $this->tagsToBeProcessInHead)) {
            $domBuilder->pushHeadToStackOfOpenElements();
            $inHeadInsertionMode = new InHeadInsertionMode();
            $inHeadInsertionMode->processToken($token, $treeConstructor, $elementFactory, $domBuilder);
            $domBuilder->removeHeadFromStackOfOpenElements();
        } else {
            $this->processUnexpectedToken($token, $treeConstructor, $elementFactory, $domBuilder);
        }
    }

    /**
     * Processes a end tag token.
     *
     * @param Token $token
     * @param TreeConstructor $treeConstructor
     * @param ElementFactory $elementFactory
     * @param DomBuilder $domBuilder
     */
    private function processEndTagToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        if ($token->getName() === 'template') {
            $inHeadInsertionMode = new InHeadInsertionMode();
            $inHeadInsertionMode->processToken($token, $treeConstructor, $elementFactory, $domBuilder);
        } elseif (in_array($token->getName(), $this->endTagsToNotBeIgnored)) {
            $this->processUnexpectedToken($token, $treeConstructor, $elementFactory, $domBuilder);
        } else {
            return;
        }
    }

    /**
     * Default behaviour for unexpected tokens.
     *
     * @param Token $token
     * @param TreeConstructor $treeConstructor
     * @param ElementFactory $elementFactory
     * @param DomBuilder $domBuilder
     */
    private function processUnexpectedToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        $domBuilder->insertNode($elementFactory->createElementFromTagName('body'));
        $treeConstructor->setInsertionMode(new InBodyInsertionMode());
        $treeConstructor->getInsertionMode()->processToken($token, $treeConstructor, $elementFactory, $domBuilder);
    }
}
