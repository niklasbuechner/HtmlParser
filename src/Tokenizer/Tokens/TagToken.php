<?php
namespace HtmlParser\Tokenizer\Tokens;

use HtmlParser\Tokenizer\Structs\AttributeStruct;

class TagToken implements Token
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var AttributeStruct
     */
    private $currentAttribute;

    public function __construct()
    {
        $this->name = '';
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->name;
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
        $this->currentAttribute = $attribute;
    }

    /**
     * @return AttributeStruct
     */
    public function getCurrentAttribute()
    {
        return $this->currentAttribute;
    }
}
