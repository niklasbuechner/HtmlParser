<?php
namespace HtmlParser\TreeConstructor;

interface Tokenizer
{
    /**
     * Switch the tokenizer to use Rcdata tokenization.
     */
    public function switchToRcdataTokenization();

    /**
     * Switch the tokenizer to use raw text tokenization.
     */
    public function switchToRawTextTokenization();

    /**
     * Switch the tokenizer to use script tokenization.
     */
    public function switchToScriptDataTokenization();

    /**
     * Switch the tokenizer to use plaintext tokenization.
     */
    public function switchToPlainTextTokenization();
}
