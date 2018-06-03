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

class InBodyInsertionMode implements InsertionMode
{
    /**
     * @var string[]
     */
    private $tagsBehavingLikeAddress;

    /**
     * @var string[]
     */
    private $tagsToBeProcessedByHeadRules;

    public function __construct()
    {
        $this->tagsBehavingLikeAddress = [
            'address',
            'article',
            'aside',
            'blockquote',
            'center',
            'details',
            'dialog',
            'dir',
            'div',
            'dl',
            'fieldset',
            'figcaption',
            'figure',
            'footer',
            'header',
            'hgroup',
            'main',
            'menu',
            'nav',
            'ol',
            'p',
            'section',
            'summary',
            'ul',
        ];
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
        } elseif ($token instanceof EndTagToken) {
            $this->processEndTagToken($token, $treeConstructor, $elementFactory, $domBuilder);
        } elseif ($token instanceof CommentToken) {
            $domBuilder->addComment($elementFactory->createCommentFromToken($token));
        } elseif ($token instanceof DoctypeToken) {
            return;
        } elseif ($token instanceof EndOfFileToken) {
            if (count($treeConstructor->getStackOfTemplateInsertionModes()) > 0) {
                $inTemplateInsertionMode = new InTemplateInsertionMode();
                $inTemplateInsertionMode->processToken($token, $treeConstructor, $elementFactory, $domBuilder);
            }
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
        } elseif ($token->getName() === 'frameset') {
            if ($domBuilder->getFramesetOkayFlag()) {
                $domBuilder->getStackOfOpenElements()[1]->removeChild($domBuilder->getStackOfOpenElements()[2]);
                while (count($domBuilder->getStackOfOpenElements()) > 2) {
                    $domBuilder->popLastElementOfStackOfOpenElements();
                }
                $domBuilder->insertNode($elementFactory->createElementFromToken($token));
            }
        } elseif (in_array($token->getName(), $this->tagsBehavingLikeAddress)) {
            $this->closePInButtonScope($domBuilder);
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
        } elseif (preg_match('/h[1-6]/', $token->getName())) {
            $this->closePInButtonScope($domBuilder);

            if (preg_match('/h[1-6]/', $domBuilder->getCurrentNode()->getName())) {
                $domBuilder->popLastElementOfStackOfOpenElements();
            }

            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
        } elseif ($token->getName() === 'pre' || $token->getName() === 'listing') {
            $this->closePInButtonScope($domBuilder);
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
            $treeConstructor->skipCharacterLineBreakToken();
            $domBuilder->setFramesetOkayFlag(false);
        } elseif ($token->getName() === 'form') {
            if ($domBuilder->getFormPointer() && $domBuilder->containsStackOfOpenElements('template')) {
                return;
            }

            $this->closePInButtonScope($domBuilder);
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));

            if (!$domBuilder->containsStackOfOpenElements('template')) {
                $domBuilder->setFormPointerToCurrentNode();
            }
        }
    }

    /**
     * Processes an end tag token.
     *
     * @param Token $token
     * @param TreeConstructor $treeConstructor
     * @param ElementFactory $elementFactory
     * @param DomBuilder $domBuilder
     */
    private function processEndTagToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
        if ($token->getName() === 'body' || $token->getName() === 'html') {
            if (!$domBuilder->containsStackOfOpenElements('body')) {
                return;
            }

            $treeConstructor->setInsertionMode(new AfterBodyInsertionMode());

            if ($token->getName() === 'html') {
                $treeConstructor->getInsertionMode()->processToken($token, $treeConstructor, $elementFactory, $domBuilder);
            }
        }
    }

    /**
     * Closes all paragraphs within the button scope.
     *
     * @param DomBuilder $domBuilder
     */
    private function closePInButtonScope($domBuilder)
    {
        if ($domBuilder->stackOfOpenElementsContainsElementInButtonScope('p')) {
            $domBuilder->generateImpliedEndTags(['p']);

            while ($domBuilder->popLastElementOfStackOfOpenElements()->getName() !== 'p') { // phpcs:ignore
                // The condition does the job.
            }
        }
    }
}
