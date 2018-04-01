<?php
namespace HtmlParser\Tokenizer\Tokens;

class CharacterToken implements Token
{
    /**
     * @var string
     */
    protected $character;

    public function __construct($character)
    {
        $this->character = $character;
    }

    /**
     * @inheritdoc
     */
    public function prepareEmit()
    {
    }

    /**
     * @return string
     */
    public function getCharacter()
    {
        return $this->character;
    }
}
