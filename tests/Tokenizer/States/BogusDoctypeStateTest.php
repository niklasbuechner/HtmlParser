<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\BogusDoctypeState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use PHPUnit\Framework\TestCase;

class BogusDoctypeStateTest extends TestCase
{
    public function testClosingDoctypeTag()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $bogusDoctypeState = new BogusDoctypeState();
        $bogusDoctypeState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $bogusDoctypeState = new BogusDoctypeState();
        $bogusDoctypeState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testAnythingElse()
    {
        $tokenizer = new TestTokenizer();

        $bogusDoctypeState = new BogusDoctypeState();
        $bogusDoctypeState->processCharacter('a', $tokenizer);
        $bogusDoctypeState->processCharacter(' ', $tokenizer);

        $this->assertNull($tokenizer->getState());
        $this->assertNull($tokenizer->getToken());
        $this->assertCount(0, $tokenizer->getTokenListener()->getEmittedTokens());
    }
}
