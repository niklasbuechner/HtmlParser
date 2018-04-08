<?php
namespace HtmlParser\Tokenizer\States;

class AttributeValueSingleQuotedState extends AbstractAttributeValueState
{
    /**
     * @inheritdoc
     */
    public function getValueDelimiter()
    {
        return '\'';
    }
}
