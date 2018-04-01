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
    }

    /**
     * @param AttributeStruct
     */
    public function setCurrentAttribute(AttributeStruct $attribute)
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
