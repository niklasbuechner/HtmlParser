<?php
namespace HtmlParser\Tokenizer\Tokens;

class TagToken implements Token
{
    /**
     * @var string
     */
    private $name;

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
        $this->name .= $character;
    }
}
