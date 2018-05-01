<?php
namespace HtmlParser\Tokenizer\States;

use HtmlParser\Tokenizer\Tokenizer;

class DoctypeSystemIdentifierDoubleQuotedState extends AbstractDoctypeSystemIdentifierQuotedState
{
    /**
     * @inheritdoc
     */
    public function getSystemIdentifierDelimiter()
    {
        return '"';
    }
}
