<?php
namespace HtmlParser\TreeConstructor\InsertionModes;

use HtmlParser\TreeConstructor\DomBuilder;
use HtmlParser\TreeConstructor\ElementFactory;
use HtmlParser\TreeConstructor\TreeConstructor;

interface InsertionMode
{
    /**
     * Processes the last emitted token.
     *
     * @param Object $token
     * @param TreeConstructor $treeConstructor
     * @param ElementFactory $elementFactory
     * @param DomBuilder $domBuilder
     */
    public function processToken($token, TreeConstructor $treeConstructor, ElementFactory $elementFactory, DomBuilder $domBuilder);
}
