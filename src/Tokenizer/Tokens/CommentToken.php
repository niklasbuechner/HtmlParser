<?php
namespace HtmlParser\Tokenizer\Tokens;

class CommentToken implements Token
{
    /**
     * @var string
     */
    protected $data;

    /**
     * Adds a string to data.
     *
     * @param string $character
     */
    public function appendCharacterToData($character)
    {
        $this->data .= $character;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function prepareEmit()
    {
    }
}
