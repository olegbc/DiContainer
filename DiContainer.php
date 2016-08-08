<?php

include ('RelatedClassInterface.php');
include ('UserProfile.php');
include ('User.php');

class DiContainer
{
    private $relatedClassParams = [];
    private $relatedClassName = '';
    private $mainClassName = '';

    public function __construct($mainClassName, $relatedClassName,  array $relatedClassParams = [])
    {
        $this->relatedClassParams = $relatedClassParams;
        $this->relatedClassName = $relatedClassName;
        $this->mainClassName = $mainClassName;
    }

    private function getRelatedClass()
    {
        $class = new $this->relatedClassName;
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

$diContainer = new DiContainer('User', 'UserProfile', ['firstName' => 'John', 'lastName' => 'Smith', 'email' => 'smith@gmail.com']);

$user = $diContainer->getMainClass();

var_dump($user);




