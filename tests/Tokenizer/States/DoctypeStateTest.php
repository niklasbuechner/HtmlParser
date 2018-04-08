<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DoctypeState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class DoctypeStateTest extends TestCase
{
    public function testSwitchToDoctypeNameState()
    {
        $tokenizer = new TestTokenizer();
        $doctypeState = new DoctypeState();

        $doctypeState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BeforeDoctypeNameState', $tokenizer->getState());
    }

    public function testTagEnd()
    {
        $tokenizer = new TestTokenizer();
        $doctypeState = new DoctypeState();

        $doctypeState->processCharacter('>', $tokenizer);

        // State is changed in BeforeDoctypeNameState again.
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
    }

    public function testMissingWhiteSpaceBeforeDoctypeName()
    {
        $tokenizer = new TestTokenizer();
        $doctypeState = new DoctypeState();

        $doctypeState->processCharacter('a', $tokenizer);

        // State is changed in BeforeDoctypeNameState again.
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DoctypeNameState', $tokenizer->getState());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $doctypeState = new DoctypeState();

        $doctypeState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }
}
