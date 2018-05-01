<?php
namespace HtmlParser\Tokenizer\Tokens;

class DoctypeToken implements Token
{
    /**
     * @var boolean
     */
    private $forceQuirks = false;

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
     * @inheritdoc
     */
    public function prepareEmit()
    {
    }

    /**
     * Set force quirks flag to on.
     */
    public function turnOnQuirksMode()
    {
        $this->forceQuirks = true;
    }

    /**
     * Returns if the quirks mode flag is on.
     *
     * @return boolean
     */
    public function isInQuirksMode()
    {
        return $this->forceQuirks;
    }

    /**
     * Adds a character to the doctype's name.
     *
     * @param string $character
     */
    public function appendCharacterToName($character)
    {
        $this->name .= mb_strtolower($character);
    }

    /**
     * Returns the doctypes name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the public identifier to an empty string.
     */
    public function initPublicIdentifier()
    {
        $this->publicIdentifier = '';
    }

    /**
     * Returns the public identifier.
     *
     * @return string
     */
    public function getPublicIdentifier()
    {
        return $this->publicIdentifier;
    }

    /**
     * Add a character to the public identifier.
     *
     * @param string $character
     */
    public function appendCharacterToPublicIdentifier($character)
    {
        $this->publicIdentifier .= $character;
    }

    /**
     * Return the system identifier.
     *
     * @return string
     */
    public function getSystemIdentifier()
    {
        return $this->systemIdentifier;
    }

    /**
     * Adds a character to the system identifier.
     *
     * @param string $character
     */
    public function appendCharacterToSystemIdentifier($character)
    {
        $this->systemIdentifier .= $character;
    }
}
