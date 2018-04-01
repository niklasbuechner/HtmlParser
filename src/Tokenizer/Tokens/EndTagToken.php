<?php
namespace HtmlParser\Tokenizer\Tokens;

class EndTagToken implements Token
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
     * @inheritdoc
     */
    public function prepareEmit()
    {
    }
}
