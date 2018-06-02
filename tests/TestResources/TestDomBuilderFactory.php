<?php
namespace HtmlParser\Tests\TestResources;

use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;

class TestDomBuilderFactory
{
    public static function getDomBuilderWithHtmlElement()
    {
        $domBuilder = new DomBuilder();
        $domBuilder->insertNode((new ElementFactory())->createElementFromTagName('html'));

        return $domBuilder;
    }

    public static function getDomBuilderWithHeadElement()
    {
        $domBuilder = self::getDomBuilderWithHtmlElement();
        $domBuilder->insertNode((new ElementFactory())->createElementFromTagName('head'));
        $domBuilder->setHeadPointerToCurrentNode();

        return $domBuilder;
    }
}
