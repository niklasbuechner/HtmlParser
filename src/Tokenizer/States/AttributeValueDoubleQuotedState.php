<?php
namespace HtmlParser\Tokenizer\States;

class AttributeValueDoubleQuotedState extends AbstractAttributeValueState
{
    /**
     * @inheritdoc
     */
    public function getValueDelimiter()
    {
        return '"';
    }
}
