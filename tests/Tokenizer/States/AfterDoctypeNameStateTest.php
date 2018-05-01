<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AfterDoctypeNameState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use PHPUnit\Framework\TestCase;

class AfterDoctypeNameStateTest extends TestCase
{
    public function testClosingOfDoctype()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $afterDoctypeNameState = new AfterDoctypeNameState();
        $afterDoctypeNameState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $afterDoctypeNameState = new AfterDoctypeNameState();
        $afterDoctypeNameState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertTrue($tokens[0]->isInQuirksMode());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testPublicKeyword()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setState(new AfterDoctypeNameState());

        $tokenizer->tokenize('public');

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterDoctypePublicKeywordState', $tokenizer->getState());
    }

    public function testSystemKeyword()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setState(new AfterDoctypeNameState());

        $tokenizer->tokenize('system');

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterDoctypeSystemKeywordState', $tokenizer->getState());
    }

    public function testInvalidCharacterSequence()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());
        $tokenizer->setState(new AfterDoctypeNameState());

        $tokenizer->tokenize('hello');

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BogusDoctypeState', $tokenizer->getState());
        $this->assertTrue($tokenizer->getToken()->isInQuirksMode());
    }
}
