<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\BeforeDoctypeNameState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class BeforeDoctypeNameStateTest extends TestCase
{
    public function testUnexpectedTagEnd()
    {
        $tokenizer = new TestTokenizer();
        $beforeDoctypeNameState = new BeforeDoctypeNameState();

        $beforeDoctypeNameState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertTrue($tokens[0]->isInQuirksMode());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $beforeDoctypeNameState = new BeforeDoctypeNameState();

        $beforeDoctypeNameState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertTrue($tokens[0]->isInQuirksMode());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testStartOfDoctypeName()
    {
        $tokenizer = new TestTokenizer();
        $beforeDoctypeNameState = new BeforeDoctypeNameState();

        $beforeDoctypeNameState->processCharacter('A', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DoctypeNameState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokenizer->getToken());
        $this->assertEquals('a', $tokenizer->getToken()->getName());
    }

    public function testWhiteSpaces()
    {
        $tokenizer = new TestTokenizer();
        $beforeDoctypeNameState = new BeforeDoctypeNameState();

        $beforeDoctypeNameState->processCharacter(' ', $tokenizer);

        $this->assertNull($tokenizer->getState());
        $this->assertNull($tokenizer->getToken());
    }
}
