<?php

class PantsProduct
{
    private $name;

    private $price;

    public function __construct($name, $price)
    {
        $this->name = $name;
        $this->price = $price;
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
}
