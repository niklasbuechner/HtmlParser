<?php
namespace HtmlParser\TreeConstructor;

interface Tokenizer
{
    /**
     * Switch the tokenizer to use Rcdata tokenization.
     */
    public function switchToRcdataTokenization();
}
