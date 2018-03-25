<?php
namespace HtmlParser\Tokenizer\Tokens;

interface Token
{
    /**
     * @return mixed
     */
    public function getValue();
}
