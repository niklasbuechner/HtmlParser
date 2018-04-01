<?php
namespace HtmlParser\Tokenizer\Tokens;

interface Token
{
    /**
     * Clean up all lose ends so that the token may be emitted.
     */
    public function prepareEmit();
}
