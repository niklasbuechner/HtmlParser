<?php
namespace HtmlParser\Tests\TreeConstructor\Nodes;

use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\TreeConstructor\Nodes\DoctypeNode;
use PHPUnit\Framework\TestCase;

class DoctypeNodeTest extends TestCase
{
    public function testNoQuirksMode()
    {
        $doctypeToken = new DoctypeToken();
        $doctypeToken->appendCharacterToName('html');

        $doctypeNode = DoctypeNode::fromToken($doctypeToken);

        $this->assertFalse($doctypeNode->getQuirksMode());
        $this->assertFalse($doctypeNode->getLimitedQuirksMode());
    }

    public function testQuirksMode()
    {
        $doctypeToken = new DoctypeToken();
        $doctypeToken->appendCharacterToName('HTML');
        $doctypeToken->appendCharacterToPublicIdentifier('-//W3C//DTD HTML 3.2 Final//EN');
        $doctypeToken->appendCharacterToSystemIdentifier('http://www.w3.org/TR/html4/strict.dtd');

        $doctypeNode = DoctypeNode::fromToken($doctypeToken);

        $this->assertTrue($doctypeNode->getQuirksMode());
        $this->assertFalse($doctypeNode->getLimitedQuirksMode());
    }

    public function testLimitedQuirksMode()
    {
        $doctypeToken = new DoctypeToken();
        $doctypeToken->appendCharacterToName('HTML');
        $doctypeToken->appendCharacterToPublicIdentifier('-//W3C//DTD XHTML 1.0 Frameset//');
        $doctypeToken->appendCharacterToSystemIdentifier('http://www.w3.org/TR/html4/strict.dtd');

        $doctypeNode = DoctypeNode::fromToken($doctypeToken);

        $this->assertFalse($doctypeNode->getQuirksMode());
        $this->assertTrue($doctypeNode->getLimitedQuirksMode());
    }
}
