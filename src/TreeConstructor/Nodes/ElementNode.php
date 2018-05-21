<?php
namespace HtmlParser\TreeConstructor\Nodes;

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
     * @param string $name
     * @param DocumentNode|ElementNode $parent
     */
    public function __construct($name, $parent, $attributes = [])
    {
        $this->name = $name;
        $this->parent = $parent;

        $this->attributes = $attributes;
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
}