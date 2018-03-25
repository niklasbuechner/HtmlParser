<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\TagOpenState;
use PHPUnit\Framework\TestCase;

class DataStateTest extends TestCase
{
    public function testOpeningHtmlTag()
    {
        $tokenizer = new TestTokenizer();
        $dataState = new DataState();

        $dataState->processCharacter('<', $tokenizer);

        $this->assertTrue($tokenizer->getState() instanceof TagOpenState);
    }
}
