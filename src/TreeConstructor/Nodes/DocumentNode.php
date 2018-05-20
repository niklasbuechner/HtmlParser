<?php
namespace HtmlParser\TreeConstructor\Nodes;

class DocumentNode
{
    /**
     * @var Node[]
     */
    private $children;

    /**
     * @var DocumentTypeNode
     */
    private $documentType;

    public function __construct()
    {
        $this->children = [];
    }

    /**
     * Adds a node as last child.
     *
     * @param Node $child
     */
    public function appendChild($child)
    {
        $this->children[] = $child;
    }

    /**
     * Returns all children.
     *
     * @return Node[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param DocumentTypeNode $docType
     */
    public function setDoctypeAttribute(DoctypeNode $docType)
    {
        $this->documentType = $docType;
    }

    /**
     * @return DocumentTypeNode
     */
    public function getDoctypeAttribute()
    {
        return $this->documentType;
    }
}
