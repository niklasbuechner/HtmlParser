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
}
