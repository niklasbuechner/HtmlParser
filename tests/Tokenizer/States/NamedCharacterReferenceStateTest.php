<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\NamedCharacterReferenceState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class NamedCharacterReferenceStateTest extends TestCase
{
    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $namedCharacterReferenceState = new NamedCharacterReferenceState();

        $namedCharacterReferenceState->processCharacter('a', $tokenizer);

        $this->assertEquals('a', $tokenizer->getTemporaryBuffer());
    }

    public function testMaximumConsumabelCharactersAreCharacterReference()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->appendToTemporaryBuffer('&');
        $tokenizer->setReturnState(new DataState());
        $namedCharacterReferenceState = new NamedCharacterReferenceState();

        $namedCharacterReferenceState->processCharacter('a', $tokenizer);
        $namedCharacterReferenceState->processCharacter('u', $tokenizer);
        $namedCharacterReferenceState->processCharacter('m', $tokenizer);
        $namedCharacterReferenceState->processCharacter('l', $tokenizer);
        $namedCharacterReferenceState->processCharacter(';', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('ä', $tokens[0]->getCharacter());
    }

    public function testCharacterReferenceIsSubstringOfConsumableCharacters()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->appendToTemporaryBuffer('&');
        $tokenizer->setReturnState(new DataState());
        $namedCharacterReferenceState = new NamedCharacterReferenceState();

        $namedCharacterReferenceState->processCharacter('a', $tokenizer);
        $namedCharacterReferenceState->processCharacter('u', $tokenizer);
        $namedCharacterReferenceState->processCharacter('m', $tokenizer);
        $namedCharacterReferenceState->processCharacter('l', $tokenizer);
        $namedCharacterReferenceState->processCharacter('l', $tokenizer);
        $namedCharacterReferenceState->processCharacter(';', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('ä', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('l', $tokens[1]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[2]);
        $this->assertEquals(';', $tokens[2]->getCharacter());
    }

    public function testIncorrectDelimiter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->appendToTemporaryBuffer('&');
        $tokenizer->setReturnState(new DataState());
        $namedCharacterReferenceState = new NamedCharacterReferenceState();

        $namedCharacterReferenceState->processCharacter('a', $tokenizer);
        $namedCharacterReferenceState->processCharacter('u', $tokenizer);
        $namedCharacterReferenceState->processCharacter('m', $tokenizer);
        $namedCharacterReferenceState->processCharacter('l', $tokenizer);
        $namedCharacterReferenceState->processCharacter('<', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('ä', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\TagOpenState', $tokenizer->getState());
    }

    public function testNonExistingCharacterReference()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->appendToTemporaryBuffer('&');
        $tokenizer->setReturnState(new DataState());
        $namedCharacterReferenceState = new NamedCharacterReferenceState();

        $namedCharacterReferenceState->processCharacter('a', $tokenizer);
        $namedCharacterReferenceState->processCharacter('b', $tokenizer);
        $namedCharacterReferenceState->processCharacter('c', $tokenizer);
        $namedCharacterReferenceState->processCharacter(';', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('&', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('a', $tokens[1]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[2]);
        $this->assertEquals('b', $tokens[2]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AmbiguousAmbersandState', $tokenizer->getState());
    }
}
