<?php
namespace HtmlParser\TreeConstructor\InsertionModes;

use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;

class InTemplateInsertionMode implements InsertionMode
{
    /**
     * @inheritdoc
     */
    public function processToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder)
    {
    }
}
