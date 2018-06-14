<?php
namespace HtmlParser\Tests\TestResources;

use HtmlParser\Tokenizer\AbstractTokenizer;
use HtmlParser\Tokenizer\States\PlainTextState;
use HtmlParser\Tokenizer\States\RcdataState;
use HtmlParser\Tokenizer\States\RawTextState;
use HtmlParser\Tokenizer\States\ScriptDataState;
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
     * @inheritdoc
     */
    public function switchToRawTextTokenization()
    {
        $this->setState(new RawTextState());
    }

    /**
     * @inheritdoc
     */
    public function switchToScriptDataTokenization()
    {
        $this->setState(new ScriptDataState());
    }

    /**
     * @inheritdoc
     */
    public function switchToPlainTextTokenization()
    {
        $this->setState(new PlainTextState());
    }

    /**
     * Checks if the state is in the rcdata state.
     *
     * @return bool
     */
    public function isInRcdataState()
    {
        return $this->getState() instanceof RcdataState;
    }

    /**
     * Checks if the state is in the raw test state.
     *
     * @return bool
     */
    public function isInRawTextState()
    {
        return $this->getState() instanceof RawTextState;
    }

    /**
     * Checks if the state is in the script data state.
     *
     * @return bool
     */
    public function isInScriptDataState()
    {
        return $this->getState() instanceof ScriptDataState;
    }

    public function isInPlaintextState()
    {
        return $this->getState() instanceof PlainTextState;
    }
}
