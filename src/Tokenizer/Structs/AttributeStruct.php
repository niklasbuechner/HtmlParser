<?php
namespace HtmlParser\Tokenizer\Structs;

class AttributeStruct
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @param string|null $name
     * @param string|null $value
     */
    public function __construct($name = null, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Appends a character to the current attribute name.
     */
    public function appendCharacterToAttributeName($character)
    {
        $this->name .= mb_strtolower($character);
    }

    public function appendCharacterToAttributeValue($character)
    {
        $this->value .= $character;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }
}
