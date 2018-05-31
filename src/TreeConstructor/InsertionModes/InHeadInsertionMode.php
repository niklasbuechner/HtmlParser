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

class InHeadInsertionMode implements InsertionMode
{
    private $acceptedEndTags;

    /**
     * List of all raw text html tags
     */
    private $rawTextTags;

    /**
     * List of all self closing tag which belong into the head.
     */
    private $selfClosingHeadTags;

    public function __construct()
    {
        $this->acceptedEndTags = [
            'body',
            'html',
            'br',
        ];

        $this->rawTextTags = [
            'style',
            'noframes',
            'noscript',
        ];

        $this->selfClosingHeadTags = [
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
        if ($token instanceof StartTagToken) {
            $this->processStartTagToken($token, $treeConstructor, $elementFactory, $domBuilder);
        } elseif ($token instanceof DoctypeToken) {
            return;
        } elseif ($token instanceof CommentToken) {
            $domBuilder->addComment($elementFactory->createCommentFromToken($token));
        } elseif ($token instanceof EndTagToken) {
            $this->processEndTagToken($token, $treeConstructor, $elementFactory, $domBuilder);
        } elseif ($token instanceof CharacterToken) {
            $domBuilder->insertCharacter($token->getCharacter());
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
        if (in_array($token->getName(), $this->selfClosingHeadTags)) {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
            $domBuilder->popLastElementOfStackOfOpenElements();
        } elseif ($token->getName() === 'title') {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));

            $treeConstructor->setOriginalInsertionMode($this);
            $treeConstructor->getTokenizer()->switchToRcdataTokenization();
            $treeConstructor->setInsertionMode(new TextInsertionMode());
        } elseif (in_array($token->getName(), $this->rawTextTags)) {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));

            $treeConstructor->setOriginalInsertionMode($this);
            $treeConstructor->getTokenizer()->switchToRawTextTokenization();
            $treeConstructor->setInsertionMode(new TextInsertionMode());
        } elseif ($token->getName() === 'script') {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));

            $treeConstructor->setOriginalInsertionMode($this);
            $treeConstructor->getTokenizer()->switchToScriptDataTokenization();
            $treeConstructor->setInsertionMode(new TextInsertionMode());
        } elseif ($token->getName() === 'html') {
            $inBodyInsertionMode = new InBodyInsertionMode();
        } elseif ($token->getName() === 'template') {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
            $domBuilder->pushMarkerOntoListOfActiveFormattingElements();
            $domBuilder->setFramesetOkayFlag(false);

            $treeConstructor->setInsertionMode(new InTemplateInsertionMode());
            $treeConstructor->addInsertionModeToStackOfTemplateInsertionModes(new InTemplateInsertionMode());
        } else {
            $this->processUnexpectedToken($token, $treeConstructor, $elementFactory, $domBuilder);
        }
    }

    /**
     * Processes an end tag token.
     *
     * @param EndTagToken $token
     * @param TreeConstructor $treeConstructor
     * @param ElementFactory $elementFactory
     * @param DomBuilder $domBuilder
     */
    private function processEndTagToken(EndTagToken $token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        if ($token->getName() === 'head') {
            $domBuilder->popLastElementOfStackOfOpenElements();
            $treeConstructor->setInsertionMode(new AfterHeadInsertionMode());
        } elseif (in_array($token->getName(), $this->acceptedEndTags)) {
            $this->processUnexpectedToken($token, $treeConstructor, $elementFactory, $domBuilder);
        } elseif ($token->getName() === 'template') {
            $domBuilder->generateImpliedEndTagsThoroughly();

            while (!($domBuilder->popLastElementOfStackOfOpenElements()->getName() === 'template')) { // phpcs:ignore
                // The condition does the job.
            }

            $domBuilder->clearListOfActiveFormattingElementsToNextMarker();
            $treeConstructor->popCurrentTemplateInsertionMode();
            $treeConstructor->resetInsertionMode();
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
