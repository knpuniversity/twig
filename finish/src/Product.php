<?php

/**
 * A test "Product" class to use when rendering things
 */
class Product
{
    private $name;

    private $imagePath;

    public function __construct($name = null, $imagePath = null)
    {
        $this->setName($name);
        $this->setImagePath($imagePath);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getImagePath()
    {
        return $this->imagePath;
    }

    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
    }
}