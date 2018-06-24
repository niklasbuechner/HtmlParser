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
    private $endTagsBehavingLikeAddress;

    /**
     * @var string[]
     */
    private $startTagsBehavingLikeAddress;

    /**
     * @var string[]
     */
    private $tagsToBeProcessedByHeadRules;

    public function __construct()
    {
        $this->endTagsBehavingLikeAddress = [
            'address',
            'article',
            'aside',
            'blockquote',
            'center',
            'dd',
            'details',
            'dialog',
            'dir',
            'div',
            'dl',
            'dt',
            'fieldset',
            'figcaption',
            'figure',
            'footer',
            'h1',
            'h2',
            'h3',
            'h4',
            'h5',
            'h6',
            'header',
            'hgroup',
            'li',
            'listing',
            'main',
            'menu',
            'nav',
            'ol',
            'pre',
            'section',
            'summary',
            'ul',
        ];
        $this->startTagsBehavingLikeAddress = [
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
        } elseif (in_array($token->getName(), $this->startTagsBehavingLikeAddress)) {
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
        } elseif ($token->getName() === 'li' || $token->getName() === 'dd' || $token->getName() === 'dt') {
            $domBuilder->setFramesetOkayFlag(false);
            $node = $domBuilder->getCurrentNode();
            $loop = true;

            while ($loop) {
                if ($node->getName() === $token->getName()) {
                    $domBuilder->generateImpliedEndTags([$token->getName()]);
                    $domBuilder->popElementsOfStackOfOpenElementsUntilElementWithName($token->getName());

                    break;
                }

                if ($domBuilder->isSpecialTag($node->getName(), ['address', 'div', 'p'])) {
                    break;
                }

                $node = $domBuilder->getParentNodeOf($node);
            }

            $this->closePInButtonScope($domBuilder);
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
        } elseif ($token->getName() === 'plaintext') {
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
            $treeConstructor->getTokenizer()->switchToPlaintextTokenization();
        } elseif ($token->getName() === 'button') {
            if ($domBuilder->stackOfOpenElementsContainsElementInButtonScope('button')) {
                $domBuilder->generateImpliedEndTags();
                $domBuilder->popElementsOfStackOfOpenElementsUntilElementWithName('button');
                $domBuilder->reconstructActiveFormattingList();
            }

            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
            $domBuilder->setFramesetOkayFlag(false);
        } elseif ($token->getName() === 'a') {
            if ($domBuilder->doesListOfActiveFormattingElementsContainBeforeMarker('a')) {
                $domBuilder->runAdoptionAgencyAlgorithm($token, $this);
            }

            $domBuilder->reconstructActiveFormattingList();
            $domBuilder->insertNode($elementFactory->createElementFromToken($token));
            $domBuilder->pushElementOntoListOfActiveFormattingElements($domBuilder->getCurrentNode());
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
        } elseif (in_array($token->getName(), $this->endTagsBehavingLikeAddress)) {
            if (!$domBuilder->containsStackOfOpenElements($token->getName())) {
                return;
            }

            $domBuilder->generateImpliedEndTags([$token->getName()]);
            $domBuilder->popElementsOfStackOfOpenElementsUntilElementWithName($token->getName());
        } elseif ($token->getName() === 'form') {
            if ($domBuilder->containsStackOfOpenElements('template')) {
                if (!$domBuilder->containsStackOfOpenElements('form')) {
                    return;
                }

                $domBuilder->generateImpliedEndTags();
                $domBuilder->popElementsOfStackOfOpenElementsUntilElementWithName('form');

                $domBuilder->reconstructActiveFormattingList();
            } else {
                $formNode = $domBuilder->getFormPointer();
                $domBuilder->clearFormPointer();

                if (!$formNode) {
                    return;
                }

                $domBuilder->generateImpliedEndTags();
                $domBuilder->popElementsOfStackOfOpenElementsUntilElement($formNode);
                $domBuilder->reconstructActiveFormattingList();
            }
        } elseif ($token->getName() === 'p') {
            if (!$domBuilder->stackOfOpenElementsContainsElementInButtonScope('p')) {
                $domBuilder->insertNode($elementFactory->createElementFromTagName('p'));
            }

            $domBuilder->generateImpliedEndTags(['p']);
            $domBuilder->popElementsOfStackOfOpenElementsUntilElementWithName('p');
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
            $domBuilder->popElementsOfStackOfOpenElementsUntilElementWithName('p');
        }
    }
}
