<?php
namespace HtmlParser\Tokenizer\States;

class DoctypePublicIdentifierDoubleQuotedState extends AbstractDoctypePublicIdentifierQuotedState
{
    /**
     * @inheritdoc
     */
    public function getPublicIdentifierDelimiter()
    {
        return '"';
    }
}
