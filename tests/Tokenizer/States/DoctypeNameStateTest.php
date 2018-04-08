<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DoctypeNameState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use PHPUnit\Framework\TestCase;

class DoctypeNameStateTest extends TestCase
{
    public function testWhiteSpace()
    {
        $tokenizer = new TestTokenizer();
        $doctypeNameState = new DoctypeNameState();

        $doctypeNameState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterDoctypeNameState', $tokenizer->getState());
    }

    public function testForEndOfDoctypeTag()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $doctypeNameState = new DoctypeNameState();
        $doctypeNameState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $doctypeNameState = new DoctypeNameState();
        $doctypeNameState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertTrue($tokens[0]->isInQuirksMode());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testAppendCharactersToDoctypeName()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $doctypeNameState = new DoctypeNameState();
        $doctypeNameState->processCharacter('H', $tokenizer);
        $doctypeNameState->processCharacter('E', $tokenizer);
        $doctypeNameState->processCharacter('L', $tokenizer);
        $doctypeNameState->processCharacter('L', $tokenizer);
        $doctypeNameState->processCharacter('O', $tokenizer);

        $this->assertEquals('hello', $tokenizer->getToken()->getName());
    }
}
