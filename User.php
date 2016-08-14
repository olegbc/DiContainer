<?php

//namespace User;


class User implements RelatedClassInterface
{
    protected $relatedClass;

    public function setRelatedClassDependencies($relatedClass) {
        $this->relatedClass = $relatedClass;
    }
}