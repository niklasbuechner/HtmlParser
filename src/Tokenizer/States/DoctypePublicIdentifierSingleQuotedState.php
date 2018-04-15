<?php
namespace HtmlParser\Tokenizer\States;

class DoctypePublicIdentifierSingleQuotedState extends AbstractDoctypePublicIdentifierQuotedState
{
    /**
     * @inheritdoc
     */
    public function getPublicIdentifierDelimiter()
    {
        return '\'';
    }
}
