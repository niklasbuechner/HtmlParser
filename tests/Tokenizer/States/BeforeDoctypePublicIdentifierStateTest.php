<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\BeforeDoctypePublicIdentifierState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use PHPUnit\Framework\TestCase;

class BeforeDoctypePublicIdentifierStateTest extends TestCase
{
    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $beforeDoctypePublicIdentifierState = new BeforeDoctypePublicIdentifierState();
        $beforeDoctypePublicIdentifierState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertTrue($tokens[0]->isInQuirksMode());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testEndOfDoctypeTag()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $beforeDoctypePublicIdentifierState = new BeforeDoctypePublicIdentifierState();
        $beforeDoctypePublicIdentifierState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertTrue($tokens[0]->isInQuirksMode());
    }

    public function testDoubleQuotedIdentifier()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $beforeDoctypePublicIdentifierState = new BeforeDoctypePublicIdentifierState();
        $beforeDoctypePublicIdentifierState->processCharacter('"', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DoctypePublicIdentifierDoubleQuotedState', $tokenizer->getState());
    }

    public function testSingleQuotedIdentifier()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $beforeDoctypePublicIdentifierState = new BeforeDoctypePublicIdentifierState();
        $beforeDoctypePublicIdentifierState->processCharacter('\'', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DoctypePublicIdentifierSingleQuotedState', $tokenizer->getState());
    }

    public function testIgnoredWhiteSpace()
    {
        $tokenizer = new TestTokenizer();
        $beforeDoctypePublicIdentifierState = new BeforeDoctypePublicIdentifierState();

        $beforeDoctypePublicIdentifierState->processCharacter(' ', $tokenizer);

        $this->assertNull($tokenizer->getState());
        $this->assertNull($tokenizer->getToken());
        $this->assertCount(0, $tokenizer->getTokenListener()->getEmittedTokens());
    }

    public function testInvalidCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $beforeDoctypePublicIdentifierState = new BeforeDoctypePublicIdentifierState();
        $beforeDoctypePublicIdentifierState->processCharacter('a', $tokenizer);

        $this->assertTrue($tokenizer->getToken()->isInQuirksMode());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BogusDoctypeState', $tokenizer->getState());
    }
}
