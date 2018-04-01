<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\TagOpenState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class DataStateTest extends TestCase
{
    public function testOpeningHtmlTag()
    {
        $tokenizer = new TestTokenizer();
        $dataState = new DataState();

        $dataState->processCharacter('<', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\TagOpenState', $tokenizer->getState());
    }

    public function testEmitCharacter()
    {
        $tokenizer = new TestTokenizer();
        $dataState = new DataState();

        $dataState->processCharacter('a', $tokenizer);
        $emittedTokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $emittedTokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $emittedTokens[0]);
        $this->assertEquals($emittedTokens[0]->getCharacter(), 'a');
    }

    public function testCharacterReferenceInText()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setState(new DataState());

        // State changes during processing!
        $tokenizer->getState()->processCharacter('&', $tokenizer);
        $tokenizer->getState()->processCharacter('a', $tokenizer);
        $tokenizer->getState()->processCharacter('u', $tokenizer);
        $tokenizer->getState()->processCharacter('m', $tokenizer);
        $tokenizer->getState()->processCharacter('l', $tokenizer);
        $tokenizer->getState()->processCharacter(';', $tokenizer);

        $emittedTokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $emittedTokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $emittedTokens[0]);
        $this->assertEquals($emittedTokens[0]->getCharacter(), 'ä');
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $dataState = new DataState();

        $dataState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);
        $emittedTokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $emittedTokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $emittedTokens[0]);
    }
}
