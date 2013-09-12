<?php

class Product
{
    private $name;
    
    private $price;

    private $postedAt;
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setPostedAt(\DateTime $posted)
    {
        $this->postedAt = $posted;
    }

    public function getPostedAt()
    {
        return $this->postedAt;
    }
}