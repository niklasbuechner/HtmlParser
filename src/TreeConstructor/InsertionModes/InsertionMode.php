<?php
namespace HtmlParser\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\Token;
use HtmlParser\TreeConstructor\TreeConstructor;

interface InsertionMode
{
    /**
     * Processes the last emitted token.
     */
    public function processToken(Token $token, TreeConstructor $treeConstructor);
}
