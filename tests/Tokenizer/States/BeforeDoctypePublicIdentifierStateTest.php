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
}
