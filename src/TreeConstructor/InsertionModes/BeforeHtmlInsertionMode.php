<?php
namespace HtmlParser\TreeConstructor\InsertionModes;

use HtmlParser\Tokenizer\Tokens\Token;
use HtmlParser\TreeConstructor\TreeConstructor;

class BeforeHtmlInsertionMode implements InsertionMode
{
    /**
     * @inheritdoc
     */
    public function processToken(Token $token, TreeConstructor $treeConstructor)
    {
    }
}
