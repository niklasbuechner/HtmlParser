<?php
namespace HtmlParser\TreeConstructor\Nodes;

use HtmlParser\Tokenizer\Tokens\DoctypeToken;

class DoctypeNode
{
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
     * @param string $name
     */
    public function __construct($name = '', $publicIdentifier = '', $systemIdentifier = '', $quirksMode = false)
    {
        $this->name = $name;
        $this->publicIdentifier = $publicIdentifier;
        $this->systemIdentifier = $systemIdentifier;
        $this->quirksMode = $quirksMode;
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
     * Factory method to create a DoctypeNode from a DoctypeToken.
     *
     * @param DoctypeToken $doctypeToken
     * @return DoctypeNode
     */
    public static function fromToken(DoctypeToken $doctypeToken)
    {
        // TODO
        $parseError = $doctypeToken->getName() !== 'html';

        $quirksMode = self::isInQuirksMode($doctypeToken);

        return new DoctypeNode(
            $doctypeToken->getName(),
            $doctypeToken->getPublicIdentifier(),
            $doctypeToken->getSystemIdentifier(),
            $quirksMode
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
        if (!$doctypeToken->isInQuirksMode() || $doctypeToken->getName() === 'html') {
            return false;
        }

        return true;
    }
}
