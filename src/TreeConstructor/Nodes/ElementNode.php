<?php
namespace HtmlParser\TreeConstructor\Nodes;

use HtmlParser\Tokenizer\Structs\AttributeStruct;

class ElementNode
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Node
     */
    private $parent;

    /**
     * @var AttributeStruct
     */
    private $attributes;

    /**
     * @var Node[]
     */
    private $children;

    /**
     * @param string $name
     * @param DocumentNode|ElementNode $parent
     */
    public function __construct($name, $attributes = [])
    {
        $this->name = $name;

        $this->attributes = $attributes;
        $this->children = [];
    }

    /**
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return AttributeStruct[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function addAttribute(AttributeStruct $attribute)
    {
        if (!isset($this->attributes[$attribute->getName()])) {
            $this->attributes[$attribute->getName()] = $attribute;
        }
    }

    /**
     * @param Node $child
     */
    public function appendChild($child)
    {
        $this->children[] = $child;
    }

    /**
     * @return ElementNode[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return Node
     */
    public function getLastChild()
    {
        if (count($this->children) === 0) {
            return;
        }

        return $this->children[count($this->children) - 1];
    }

    /**
     * Removes a child element.
     *
     * @param ElementNode $child
     */
    public function removeChild(ElementNode $child)
    {
        foreach ($this->children as $index => $existingChild) {
            if ($existingChild === $child) {
                unset($this->children[$index]);
                $this->children = array_values($this->children);

                return;
            }
        }
    }
}
