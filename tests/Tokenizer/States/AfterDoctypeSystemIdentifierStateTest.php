<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AfterDoctypeSystemIdentifierState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use PHPUnit\Framework\TestCase;

class AfterDoctypeSystemIdentifierStateTest extends TestCase
{
    public function testWhiteSpace()
    {
        $tokenizer = new TestTokenizer();
        $afterDoctypeSystemIdentifierState = new AfterDoctypeSystemIdentifierState();
        $afterDoctypeSystemIdentifierState->processCharacter(' ', $tokenizer);

        $this->assertNull($tokenizer->getState());
        $this->assertCount(0, $tokenizer->getTokenListener()->getEmittedTokens());
    }

    public function testEndOfDoctype()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken);

        $afterDoctypeSystemIdentifierState = new AfterDoctypeSystemIdentifierState();
        $afterDoctypeSystemIdentifierState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
    }

    public function testUnexpectedEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken);

        $afterDoctypeSystemIdentifierState = new AfterDoctypeSystemIdentifierState();
        $afterDoctypeSystemIdentifierState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testUnexpectedCharacter()
    {
        $tokenizer = new TestTokenizer();

        $afterDoctypeSystemIdentifierState = new AfterDoctypeSystemIdentifierState();
        $afterDoctypeSystemIdentifierState->processCharacter('a', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BogusDoctypeState', $tokenizer->getState());
    }
}
