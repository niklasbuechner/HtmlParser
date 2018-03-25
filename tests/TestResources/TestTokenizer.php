<?php
namespace HtmlParser\Tests\TestResources;

use HtmlParser\Tokenizer\States\State;
use HtmlParser\Tokenizer\AbstractTokenizer;
use HtmlParser\Tokenizer\TokenListener;
use HtmlParser\Tokenizer\Tokens\Token;

class TestTokenizer extends AbstractTokenizer
{
    public function __construct()
    {
        parent::__construct(new TestTokenListener());
    }

    /**
     * @inheritdoc
     */
    public function tokenize($htmlString)
    {
        throw new Exception('->tokenize called on TestTokenizer');
    }

    /**
     * Returns the current TokenListener.
     *
     * @return TokenListener
     */
    public function getTokenListener()
    {
        return $this->listener;
    }
}
