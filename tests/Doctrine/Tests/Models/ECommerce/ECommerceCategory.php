<?php

declare(strict_types=1);

namespace Doctrine\Tests\Models\ECommerce;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * ECommerceCategory
 * Represents a tag applied on particular products.
 *
 * @Entity
 * @Table(name="ecommerce_categories")
 */
class ECommerceCategory
{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Column(type="string", length=50)
     */
    private $name;

    /**
     * @psalm-var Collection<int, ECommerceProduct>
     * @ManyToMany(targetEntity="ECommerceProduct", mappedBy="categories")
     */
    private $products;

    /** @OneToMany(targetEntity="ECommerceCategory", mappedBy="parent", cascade={"persist"}) */
    private $children;

    /**
     * @var ECommerceCategory
     * @ManyToOne(targetEntity="ECommerceCategory", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function addProduct(ECommerceProduct $product): void
    {
        if (! $this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCategory($this);
        }
    }

    public function removeProduct(ECommerceProduct $product): void
    {
        $removed = $this->products->removeElement($product);
        if ($removed) {
            $product->removeCategory($this);
        }
    }

    public function getProducts()
    {
        return $this->products;
    }

    private function setParent(ECommerceCategory $parent): void
    {
        $this->parent = $parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function addChild(ECommerceCategory $child): void
    {
        $this->children[] = $child;
        $child->setParent($this);
    }

    /** does not set the owning side. */
    public function brokenAddChild(ECommerceCategory $child): void
    {
        $this->children[] = $child;
    }

    public function removeChild(ECommerceCategory $child): void
    {
        $removed = $this->children->removeElement($child);
        if ($removed) {
            $child->removeParent();
        }
    }

    private function removeParent(): void
    {
        $this->parent = null;
    }
}
