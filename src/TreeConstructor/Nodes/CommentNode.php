<?php
namespace HtmlParser\TreeConstructor\Nodes;

class CommentNode
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
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }
}
