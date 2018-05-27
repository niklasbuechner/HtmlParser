<?php
namespace HtmlParser\TreeConstructor\Nodes;

class TextNode
{
    /**
     * @var string
     */
    private $data;

    /**
     * @param string $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param string $data
     */
    public function appendToData($data)
    {
        $this->data .= $data;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }
}
