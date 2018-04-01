<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AttributeValueDoubleQuotedState;
use HtmlParser\Tokenizer\States\CharacterReferenceState;
use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class CharacterReferenceStateTest extends TestCase
{
    public function testStartNamedCharacterReference()
    {
        $tokenizer = new TestTokenizer();
        $characterReferenceState = new CharacterReferenceState($tokenizer);

        $characterReferenceState->processCharacter('u', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\NamedCharacterReferenceState', $tokenizer->getState());
    }

    public function testStartDecimalNumericCharacterReference()
    {
        $tokenizer = new TestTokenizer();
        $characterReferenceState = new CharacterReferenceState($tokenizer);

        $characterReferenceState->processCharacter('#', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\NumericCharacterReferenceState', $tokenizer->getState());
    }

    public function testNamedCharacterReference()
    {
        $tagToken = new StartTagToken();
        $tagToken->setCurrentAttribute(new AttributeStruct());

        $tokenizer = new TestTokenizer();
        $tokenizer->setReturnState(new AttributeValueDoubleQuotedState());
        $tokenizer->setState(new CharacterReferenceState($tokenizer));
        $tokenizer->setCurrentToken($tagToken);

        // Careful, the state changes during the character processing.
        // It is set on the tokenizer.
        $tokenizer->getState()->processCharacter('u', $tokenizer);
        $tokenizer->getState()->processCharacter('u', $tokenizer);
        $tokenizer->getState()->processCharacter('m', $tokenizer);
        $tokenizer->getState()->processCharacter('l', $tokenizer);
        $tokenizer->getState()->processCharacter(';', $tokenizer);

        $this->assertEquals('ü', $tagToken->getCurrentAttribute()->getValue());
    }

    public function testDecimalNumericReference()
    {
        $tagToken = new StartTagToken();
        $tagToken->setCurrentAttribute(new AttributeStruct());

        $tokenizer = new TestTokenizer();
        $tokenizer->setReturnState(new AttributeValueDoubleQuotedState());
        $tokenizer->setState(new CharacterReferenceState($tokenizer));
        $tokenizer->setCurrentToken($tagToken);

        // Careful, the state changes during the character processing.
        // It is set on the tokenizer.
        $tokenizer->getState()->processCharacter('#', $tokenizer);
        $tokenizer->getState()->processCharacter('2', $tokenizer);
        $tokenizer->getState()->processCharacter('5', $tokenizer);
        $tokenizer->getState()->processCharacter('2', $tokenizer);
        $tokenizer->getState()->processCharacter(';', $tokenizer);

        $this->assertEquals('ü', $tagToken->getCurrentAttribute()->getValue());
    }

    public function testHexadecimalNumericReference()
    {
        $tagToken = new StartTagToken();
        $tagToken->setCurrentAttribute(new AttributeStruct());

        $tokenizer = new TestTokenizer();
        $tokenizer->setReturnState(new AttributeValueDoubleQuotedState());
        $tokenizer->setState(new CharacterReferenceState($tokenizer));
        $tokenizer->setCurrentToken($tagToken);

        // Careful, the state changes during the character processing.
        // It is set on the tokenizer.
        $tokenizer->getState()->processCharacter('#', $tokenizer);
        $tokenizer->getState()->processCharacter('x', $tokenizer);
        $tokenizer->getState()->processCharacter('E', $tokenizer);
        $tokenizer->getState()->processCharacter('4', $tokenizer);
        $tokenizer->getState()->processCharacter(';', $tokenizer);

        $this->assertEquals('ä', $tagToken->getCurrentAttribute()->getValue());
    }
}
