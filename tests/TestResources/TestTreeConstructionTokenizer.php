<?php
namespace HtmlParser\Tests\TestResources;

use HtmlParser\Tokenizer\AbstractTokenizer;
use HtmlParser\Tokenizer\States\RcdataState;
use HtmlParser\TreeConstructor\Tokenizer;

class TestTreeConstructionTokenizer extends AbstractTokenizer implements Tokenizer
{
    /**
     * There is no need to set a token listener, therefore override the
     * default constructor.
     */
    public function __construct()
    {
    }

    /**
     * @inheritdoc
     */
    public function switchToRcdataTokenization()
    {
        $this->setState(new RcdataState());
    }

    /**
     * Checks if the state is an Rcdata state.
     *
     * @return bool
     */
    public function isInRcdataState()
    {
        return $this->getState() instanceof RcdataState;
    }
}
