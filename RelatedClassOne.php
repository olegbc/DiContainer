<?php

include ('RelatedClassTwo.php');

class RelatedClassOne
{
    private  $rel;

    public function __construct()
    {
        $this->rel = new RelatedClassTwo();
    }

}