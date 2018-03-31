<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CharacterReferenceState;
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
}
