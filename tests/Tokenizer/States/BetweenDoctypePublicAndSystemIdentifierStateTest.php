<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\BetweenDoctypePublicAndSystemIdentifierState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use PHPUnit\Framework\TestCase;

class BetweenDoctypePublicAndSystemIdentifierStateTest extends TestCase
{
    public function testEndOfDoctype()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $betweenDoctypePublicAndSystemIdentifierState = new BetweenDoctypePublicAndSystemIdentifierState();
        $betweenDoctypePublicAndSystemIdentifierState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $betweenDoctypePublicAndSystemIdentifierState = new BetweenDoctypePublicAndSystemIdentifierState();
        $betweenDoctypePublicAndSystemIdentifierState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testStartOfSystemIdentifierDoubleQuoted()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $betweenDoctypePublicAndSystemIdentifierState = new BetweenDoctypePublicAndSystemIdentifierState();
        $betweenDoctypePublicAndSystemIdentifierState->processCharacter('"', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DoctypeSystemIdentifierDoubleQuotedState', $tokenizer->getState());
        $this->assertEquals('', $tokenizer->getToken()->getSystemIdentifier());
    }

    public function testStartOfSystemIdentifierSingleQuoted()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $betweenDoctypePublicAndSystemIdentifierState = new BetweenDoctypePublicAndSystemIdentifierState();
        $betweenDoctypePublicAndSystemIdentifierState->processCharacter('\'', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DoctypeSystemIdentifierSingleQuotedState', $tokenizer->getState());
        $this->assertEquals('', $tokenizer->getToken()->getSystemIdentifier());
    }
}
