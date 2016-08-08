<?php

//namespace User;


class User implements RelatedClassInterface
{
    protected $relatedClass;

    public function setRelatedClassDependency($relatedClass) {
        $this->relatedClass = $relatedClass;
    }
}