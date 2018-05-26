<?php
namespace HtmlParser\TreeConstructor;

use HtmlParser\Tokenizer\Tokens\CommentToken;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\Nodes\CommentNode;
use HtmlParser\TreeConstructor\Nodes\DoctypeNode;
use HtmlParser\TreeConstructor\Nodes\ElementNode;

class ElementFactory
{
    private $quirksModePublicIdentifier;
    private $quirksModeSystemIdentifier;
    private $quirksModePublicIdentifierStartingWith;
    private $quirksModePublicIdentifierWithoutSystemIdentifier;
    private $limitedQuirksModePublicIdentifierStartingWith;
    private $limitedQuirksModePublicIdentifierWithSystemIdentifierStartingWith;

    /**
     * Creates a node form a start tag token.
     *
     * @param StartTagToken $token
     * @return ElementNode
     */
    public function createElementFromToken(StartTagToken $token)
    {
        return new ElementNode($token->getName(), $token->getAttributes());
    }

    /**
     * Creates a node from a tag name.
     *
     * @param string $name
     * @return ElementNode
     */
    public function createElementFromTagName($name)
    {
        return new ElementNode($name);
    }

    /**
     * Returns a comment node created from the comment token.
     *
     * @param CommentToken $comment
     * @return CommentNode
     */
    public function createCommentNodeFromToken(CommentToken $comment)
    {
        return new CommentNode($comment->getData());
    }

    /**
     * Factory method to create a DoctypeNode from a DoctypeToken.
     *
     * @param DoctypeToken $doctypeToken
     * @return DoctypeNode
     */
    public function createDoctypeFromToken(DoctypeToken $doctypeToken)
    {
        // TODO
        $parseError = $doctypeToken->getName() !== 'html';

        return new DoctypeNode(
            $doctypeToken->getName(),
            $doctypeToken->getPublicIdentifier(),
            $doctypeToken->getSystemIdentifier(),
            $this->isInQuirksMode($doctypeToken),
            $this->isInLimitedQuirksMode($doctypeToken)
        );
    }

    /**
     * Loads the definitions for document types which lead to a document
     * with quirks mode.
     */
    public function __construct()
    {
        $this->quirksModePublicIdentifier = [
            mb_strtolower('-//W3O//DTD W3 HTML Strict 3.0//EN//'),
            mb_strtolower('-/W3C/DTD HTML 4.0 Transitional/EN'),
            mb_strtolower('HTML'),
        ];

        $this->quirksModeSystemIdentifier = [
            mb_strtolower('http://www.ibm.com/data/dtd/v11/ibmxhtml1-transitional.dtd'),
        ];

        $this->quirksModePublicIdentifierStartingWith = [
            mb_strtolower('+//Silmaril//dtd html Pro v0r11 19970101//'),
            mb_strtolower('-//AS//DTD HTML 3.0 asWedit + extensions//'),
            mb_strtolower('-//AdvaSoft Ltd//DTD HTML 3.0 asWedit + extensions//'),
            mb_strtolower('-//IETF//DTD HTML 2.0 Level 2//'),
            mb_strtolower('-//IETF//DTD HTML 2.0 Strict Level 1//'),
            mb_strtolower('-//IETF//DTD HTML 2.0 Strict Level 2//"'),
            mb_strtolower('-//IETF//DTD HTML 2.0 Strict//'),
            mb_strtolower('-//IETF//DTD HTML 2.0//'),
            mb_strtolower('-//IETF//DTD HTML 2.1E//'),
            mb_strtolower('-//IETF//DTD HTML 3.0//'),
            mb_strtolower('-//IETF//DTD HTML 3.2 Final//'),
            mb_strtolower('-//IETF//DTD HTML 3.2//'),
            mb_strtolower('-//IETF//DTD HTML 3//'),
            mb_strtolower('-//IETF//DTD HTML Level 0//'),
            mb_strtolower('-//IETF//DTD HTML Level 1//'),
            mb_strtolower('-//IETF//DTD HTML Level 2//'),
            mb_strtolower('-//IETF//DTD HTML Level 3//'),
            mb_strtolower('-//IETF//DTD HTML Strict Level 0//'),
            mb_strtolower('-//IETF//DTD HTML Strict Level 1//'),
            mb_strtolower('-//IETF//DTD HTML Strict Level 2//'),
            mb_strtolower('-//IETF//DTD HTML Strict Level 3//'),
            mb_strtolower('-//IETF//DTD HTML Strict//'),
            mb_strtolower('-//IETF//DTD HTML//'),
            mb_strtolower('-//Metrius//DTD Metrius Presentational//'),
            mb_strtolower('-//Microsoft//DTD Internet Explorer 2.0 HTML Strict//'),
            mb_strtolower('-//Microsoft//DTD Internet Explorer 2.0 HTML//'),
            mb_strtolower('-//Microsoft//DTD Internet Explorer 2.0 Tables//'),
            mb_strtolower('-//Microsoft//DTD Internet Explorer 3.0 HTML Strict//'),
            mb_strtolower('-//Microsoft//DTD Internet Explorer 3.0 HTML//'),
            mb_strtolower('-//Microsoft//DTD Internet Explorer 3.0 Tables//'),
            mb_strtolower('-//Netscape Comm. Corp.//DTD HTML//'),
            mb_strtolower('-//Netscape Comm. Corp.//DTD Strict HTML//'),
            mb_strtolower('-//O\'Reilly and Associates//DTD HTML 2.0//'),
            mb_strtolower('-//O\'Reilly and Associates//DTD HTML Extended 1.0//'),
            mb_strtolower('-//O\'Reilly and Associates//DTD HTML Extended Relaxed 1.0//'),
            mb_strtolower('-//SQ//DTD HTML 2.0 HoTMetaL + extensions//'),
            mb_strtolower('-//SoftQuad Software//DTD HoTMetaL PRO 6.0::19990601::extensions to HTML 4.0//'),
            mb_strtolower('-//SoftQuad//DTD HoTMetaL PRO 4.0::19971010::extensions to HTML 4.0//'),
            mb_strtolower('-//Spyglass//DTD HTML 2.0 Extended//'),
            mb_strtolower('-//Sun Microsystems Corp.//DTD HotJava HTML//'),
            mb_strtolower('-//Sun Microsystems Corp.//DTD HotJava Strict HTML//'),
            mb_strtolower('-//W3C//DTD HTML 3 1995-03-24//'),
            mb_strtolower('-//W3C//DTD HTML 3.2 Draft//'),
            mb_strtolower('-//W3C//DTD HTML 3.2 Final//'),
            mb_strtolower('-//W3C//DTD HTML 3.2//'),
            mb_strtolower('-//W3C//DTD HTML 3.2S Draft//'),
            mb_strtolower('-//W3C//DTD HTML 4.0 Frameset//'),
            mb_strtolower('-//W3C//DTD HTML 4.0 Transitional//'),
            mb_strtolower('-//W3C//DTD HTML Experimental 19960712//'),
            mb_strtolower('-//W3C//DTD HTML Experimental 970421//'),
            mb_strtolower('-//W3C//DTD W3 HTML//'),
            mb_strtolower('-//W3O//DTD W3 HTML 3.0//'),
            mb_strtolower('-//WebTechs//DTD Mozilla HTML 2.0//'),
            mb_strtolower('-//WebTechs//DTD Mozilla HTML//'),
        ];

        $this->quirksModePublicIdentifierWithoutSystemIdentifier = [
            mb_strtolower('-//W3C//DTD HTML 4.01 Frameset//'),
            mb_strtolower('-//W3C//DTD HTML 4.01 Transitional//'),
        ];

        $this->limitedQuirksModePublicIdentifierStartingWith = [
            mb_strtolower('-//W3C//DTD XHTML 1.0 Frameset//'),
            mb_strtolower('-//W3C//DTD XHTML 1.0 Transitional//'),
        ];

        $this->limitedQuirksModePublicIdentifierWithSystemIdentifierStartingWith = [
            mb_strtolower('-//W3C//DTD HTML 4.01 Frameset//'),
            mb_strtolower('-//W3C//DTD HTML 4.01 Transitional//'),
        ];
    }

    /**
     * Determines whether the document is in quirks mode.
     *
     * @param DoctypeToken $doctypeToken
     * @return boolean
     */
    private function isInQuirksMode(DoctypeToken $doctypeToken)
    {
        $publicIdentifier = mb_strtolower($doctypeToken->getPublicIdentifier());
        $systemIdentifier = mb_strtolower($doctypeToken->getSystemIdentifier());

        if ($doctypeToken->getName() === 'html' && $publicIdentifier === '' && $systemIdentifier === '') {
            return false;
        }

        if (in_array($publicIdentifier, $this->quirksModePublicIdentifier)) {
            return true;
        }

        if (in_array($systemIdentifier, $this->quirksModeSystemIdentifier)) {
            return true;
        }

        foreach ($this->quirksModePublicIdentifierStartingWith as $quirksModePublicIdentifier) {
            if (mb_substr($publicIdentifier, 0, mb_strlen($quirksModePublicIdentifier)) === $quirksModePublicIdentifier) {
                return true;
            }
        }

        if ($systemIdentifier === '') {
            foreach ($this->quirksModePublicIdentifierWithoutSystemIdentifier as $quirksModePublicIdentifier) {
                if (mb_substr($publicIdentifier, 0, mb_strlen($quirksModePublicIdentifier)) === $quirksModePublicIdentifier) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determines whether the document is in limited quirks mode.
     *
     * @param DoctypeToken $doctypeToken
     * @return boolean
     */
    private function isInLimitedQuirksMode(DoctypeToken $doctypeToken)
    {
        $publicIdentifier = mb_strtolower($doctypeToken->getPublicIdentifier());
        $systemIdentifier = mb_strtolower($doctypeToken->getSystemIdentifier());

        foreach ($this->limitedQuirksModePublicIdentifierStartingWith as $quirksModePublicIdentifier) {
            if (mb_substr($publicIdentifier, 0, mb_strlen($quirksModePublicIdentifier)) === $quirksModePublicIdentifier) {
                return true;
            }
        }

        if ($systemIdentifier === '') {
            return false;
        }

        foreach ($this->limitedQuirksModePublicIdentifierWithSystemIdentifierStartingWith as $quirksModePublicIdentifier) {
            if (mb_substr($publicIdentifier, 0, mb_strlen($quirksModePublicIdentifier)) === $quirksModePublicIdentifier) {
                return true;
            }
        }

        return false;
    }
}
