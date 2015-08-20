<?php

class PantsProduct
{
    private $name;

    private $price;

    private $quantity;

    private $description;

    public function __construct($name, $price, $quantity = 0)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getFutureReleaseDate()
    {
        // our designers don't know when things will get released yet, so just
        // always say it will be 1 week from now!
        return new \DateTime('+1 week');
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }
}
