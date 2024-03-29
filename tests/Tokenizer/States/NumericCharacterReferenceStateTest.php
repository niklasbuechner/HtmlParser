<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\NumericCharacterReferenceState;
use PHPUnit\Framework\TestCase;

class NumericCharacterReferenceStateTest extends TestCase
{
    public function testStartOfHexaDecimalCharacterReferenceState()
    {
        $tokenizer = new TestTokenizer();
        $numericCharacterReferenceState = new NumericCharacterReferenceState($tokenizer);

        $numericCharacterReferenceState->processCharacter('x', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\HexadecimalCharacterReferenceStartState', $tokenizer->getState());
    }

    public function testStartOfDecimalCharacterReferenceState()
    {
        $tokenizer = new TestTokenizer();
        $numericCharacterReferenceState = new NumericCharacterReferenceState($tokenizer);

        $numericCharacterReferenceState->processCharacter('0', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DecimalCharacterReferenceState', $tokenizer->getState());
    }
}
