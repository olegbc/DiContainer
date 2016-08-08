<?php

include ('RelatedClassInterface.php');
include ('User.php');

class DiContainer
{
    public $relatedClassParams = [];
    public $mainClassName = '';

    public function __construct($mainClassName, array $relatedClassParams = [])
    {
        $this->relatedClassParams = $relatedClassParams;
        $this->mainClassName = $mainClassName;
    }

    public function getRelatedClass()
    {
        $class = new $this->mainClassName;
        foreach ($this->relatedClassParams as $key=>$val) {
            $class->$key = $val;
        }

        return $class;
    }

    public function getMainClass()
    {
        $mainClassObject = new $this->mainClassName;
        $mainClassObject->setRelatedClassDependency($this->getRelatedClass());

        return $mainClassObject;
    }
}

$User = new DiContainer('User', ['firstName' => 'John', 'lastName' => 'Smith', 'email' => 'smith@gmail.com']);

$User->getMainClass();

var_dump($User);




