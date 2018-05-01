<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class DoctypeSystemIdentifierSingleQuotedState extends AbstractDoctypeSystemIdentifierQuotedState
{
    /**
     * @inheritdoc
     */
    public function getSystemIdentifierDelimiter()
    {
        return '\'';
    }
}
