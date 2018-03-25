<?php
namespace HtmlParser\Tokenizer\Structs;

class AttributeStruct
{
    /**
     * @var string
     */
    private $name;

    /**
     * Appends a character to the current attribute name.
     */
    public function appendCharacterToAttributeName($character)
    {
        $this->name .= mb_strtolower($character);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
