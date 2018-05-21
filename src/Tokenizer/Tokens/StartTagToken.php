<?php
namespace HtmlParser\Tokenizer\Tokens;

use HtmlParser\Tokenizer\Structs\AttributeStruct;

class StartTagToken implements Token
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var AttributeStruct[]
     */
    private $attributes;

    /**
     * @var AttributeStruct
     */
    private $currentAttribute;

    /**
     * @var boolean
     */
    private $selfClosing = false;

    public function __construct()
    {
        $this->name = '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function appendCharacterToName($character)
    {
        $this->name .= mb_strtolower($character);
        $this->attributes = [];
    }

    /**
     * @param AttributeStruct
     */
    public function addAttribute(AttributeStruct $attribute)
    {
        $this->saveCurrentAttribute();
        $this->currentAttribute = $attribute;
    }

    /**
     * @return AttributeStruct
     */
    public function getCurrentAttribute()
    {
        return $this->currentAttribute;
    }

    /**
     * @return AttributeStruct[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @inheritdoc
     */
    public function prepareEmit()
    {
        $this->saveCurrentAttribute();
    }

    /**
     * Defines that the tag does not need an end tag.
     */
    public function setAsSelfClosing()
    {
        $this->selfClosing = true;
    }

    /**
     * Checks if the tag need an end tag.
     */
    public function isSelfClosing()
    {
        return $this->selfClosing;
    }

    /**
     * Adds the current attribute to the attribute stack and resets the current attribute pointer.
     */
    private function saveCurrentAttribute()
    {
        if ($this->currentAttribute) {
            $this->attributes[] = $this->currentAttribute;
            $this->currentAttribute = null;
        }
    }
}
