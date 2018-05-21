<?php
namespace HtmlParser\TreeConstructor\Nodes;

use HtmlParser\Tokenizer\Tokens\DoctypeToken;

class DoctypeNode
{
    private static $QUIRKS_MODE_PUBLIC_IDENTIFIER;
    private static $QUIRKS_MODE_SYSTEM_IDENTIFIER;
    private static $QUIRKS_MODE_PUBLIC_IDENTIFIER_STARTING_WITH;
    private static $QUIRKS_MODE_PUBLIC_IDENTIFIER_WITHOUT_SYSTEM_IDENTIFIER;
    private static $LIMITED_QUIRKS_MODE_PUBLIC_IDENTIFIER_STARTING_WITH;
    private static $LIMITED_QUIRKS_MODE_PUBLIC_IDENTIFIER_WITH_SYSTEM_IDENTIFIER_STARTING_WITH;

    /**
     * Loads the definitions for document types which lead to a document
     * with quirks mode.
     */
    private static function loadQuirlsModeData()
    {
        self::$QUIRKS_MODE_PUBLIC_IDENTIFIER = [
            mb_strtolower('-//W3O//DTD W3 HTML Strict 3.0//EN//'),
            mb_strtolower('-/W3C/DTD HTML 4.0 Transitional/EN'),
            mb_strtolower('HTML'),
        ];

        self::$QUIRKS_MODE_SYSTEM_IDENTIFIER = [
            mb_strtolower('http://www.ibm.com/data/dtd/v11/ibmxhtml1-transitional.dtd'),
        ];

        self::$QUIRKS_MODE_PUBLIC_IDENTIFIER_STARTING_WITH = [
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

        self::$QUIRKS_MODE_PUBLIC_IDENTIFIER_WITHOUT_SYSTEM_IDENTIFIER = [
            mb_strtolower('-//W3C//DTD HTML 4.01 Frameset//'),
            mb_strtolower('-//W3C//DTD HTML 4.01 Transitional//'),
        ];

        self::$LIMITED_QUIRKS_MODE_PUBLIC_IDENTIFIER_STARTING_WITH = [
            mb_strtolower('-//W3C//DTD XHTML 1.0 Frameset//'),
            mb_strtolower('-//W3C//DTD XHTML 1.0 Transitional//'),
        ];

        self::$LIMITED_QUIRKS_MODE_PUBLIC_IDENTIFIER_WITH_SYSTEM_IDENTIFIER_STARTING_WITH = [
            mb_strtolower('-//W3C//DTD HTML 4.01 Frameset//'),
            mb_strtolower('-//W3C//DTD HTML 4.01 Transitional//'),
        ];
    }

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $publicIdentifier;

    /**
     * @var string
     */
    private $systemIdentifier;

    /**
     * @var boolean
     */
    private $quirksMode;

    /**
     * @var $limitedQuirksMode
     */
    private $limitedQuirksMode;

    /**
     * @param string $name
     */
    public function __construct($name = '', $publicIdentifier = '', $systemIdentifier = '', $quirksMode = false, $limitedQuirksMode = false)
    {
        $this->name = $name;
        $this->publicIdentifier = $publicIdentifier;
        $this->systemIdentifier = $systemIdentifier;
        $this->quirksMode = $quirksMode;
        $this->limitedQuirksMode = $limitedQuirksMode;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPublicIdentifier()
    {
        return $this->publicIdentifier;
    }

    /**
     * @return string
     */
    public function getSystemIdentifier()
    {
        return $this->systemIdentifier;
    }

    /**
     * @return boolean
     */
    public function getQuirksMode()
    {
        return $this->quirksMode;
    }

    /**
     * @return boolean
     */
    public function getLimitedQuirksMode()
    {
        return $this->limitedQuirksMode;
    }

    /**
     * Factory method to create a DoctypeNode from a DoctypeToken.
     *
     * @param DoctypeToken $doctypeToken
     * @return DoctypeNode
     */
    public static function fromToken(DoctypeToken $doctypeToken)
    {
        // TODO
        $parseError = $doctypeToken->getName() !== 'html';

        return new DoctypeNode(
            $doctypeToken->getName(),
            $doctypeToken->getPublicIdentifier(),
            $doctypeToken->getSystemIdentifier(),
            self::isInQuirksMode($doctypeToken),
            self::isInLimitedQuirksMode($doctypeToken)
        );
    }

    /**
     * Determines whether the document is in quirks mode.
     *
     * @param DoctypeToken $doctypeToken
     * @return boolean
     */
    private static function isInQuirksMode(DoctypeToken $doctypeToken)
    {
        self::loadQuirlsModeData();

        $publicIdentifier = mb_strtolower($doctypeToken->getPublicIdentifier());
        $systemIdentifier = mb_strtolower($doctypeToken->getSystemIdentifier());

        if ($doctypeToken->getName() === 'html' && $publicIdentifier === '' && $systemIdentifier === '') {
            return false;
        }

        if (in_array($publicIdentifier, self::$QUIRKS_MODE_PUBLIC_IDENTIFIER)) {
            return true;
        }

        if (in_array($systemIdentifier, self::$QUIRKS_MODE_SYSTEM_IDENTIFIER)) {
            return true;
        }

        foreach (self::$QUIRKS_MODE_PUBLIC_IDENTIFIER_STARTING_WITH as $quirksModePublicIdentifier) {
            if (mb_substr($publicIdentifier, 0, mb_strlen($quirksModePublicIdentifier)) === $quirksModePublicIdentifier) {
                return true;
            }
        }

        if ($systemIdentifier === '') {
            foreach (self::QUIRKS_MODE_PUBLIC_IDENTIFIER_WITHOUT_SYSTEM_IDENTIFIER as $quirksModePublicIdentifier) {
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
    private static function isInLimitedQuirksMode(DoctypeToken $doctypeToken)
    {
        self::loadQuirlsModeData();

        $publicIdentifier = mb_strtolower($doctypeToken->getPublicIdentifier());
        $systemIdentifier = mb_strtolower($doctypeToken->getSystemIdentifier());

        foreach (self::$LIMITED_QUIRKS_MODE_PUBLIC_IDENTIFIER_STARTING_WITH as $quirksModePublicIdentifier) {
            if (mb_substr($publicIdentifier, 0, mb_strlen($quirksModePublicIdentifier)) === $quirksModePublicIdentifier) {
                return true;
            }
        }

        if ($systemIdentifier === '') {
            return false;
        }

        foreach (self::$LIMITED_QUIRKS_MODE_PUBLIC_IDENTIFIER_WITH_SYSTEM_IDENTIFIER_STARTING_WITH as $quirksModePublicIdentifier) {
            if (mb_substr($publicIdentifier, 0, mb_strlen($quirksModePublicIdentifier)) === $quirksModePublicIdentifier) {
                return true;
            }
        }

        return false;
    }
}
