<?php

include ('RelatedClassInterface.php');
include ('RelatedClassOne.php');
include ('Container.php');
include ('User.php');

class DiContainer implements Container
{
    public static function getInstance()
    {
        return new static();
    }
    private function __construct()
    {
    }

    private static $class;
    //private  $relatedClassName = 'RelatedClassOne';

    public function get($class)
    {
        if(!is_null(self::$class)) {
            return $this->class;
        }
        return false;
    }

    /**
     * @param string $class
     * @param array $arguments
     * @return mixed
     */
    public function create($class, array $arguments = [])
    {
        if(is_null(self::$class)) {
            $mainClassObject = new $class(new GuestUser(), $arguments);
            $this->class = $mainClassObject;
            //$mainClassObject->setRelatedClassDependencies($this->getRelatedClass());
            return $mainClassObject;
        }

        return self::$class;
    }
}


class GuestUser {
    private $name;
    /**
     * @var Bob
     */
    private $bob;

    public function __construct(Bob $bob, $name = 'guest')
    {
        $this->name = $name;
        $this->bob = $bob;
    }

}

class TestUser {

    private $name;
    /**
     * @var GuestUser
     */
    private $sub;
    private $test;

    public function __construct(GuestUser $sub, $name, $test)
    {
        $this->name = $name;
        $this->sub = $sub;
        $this->test = $test;
    }

}

class Bob {
    /**
     * @var Jon
     */
    private $jon;

    public function __construct(Jon $jon)
    {
        $this->jon = $jon;
    }

}

class Jon {

}

$diContainer = DiContainer::getInstance();

$user = $diContainer->create(TestUser::class, ['test' => 123, 'name' => 'Bob']);
$user2 = $diContainer->create(TestUser::class, ['test' => 888, 'name' => 'Jon']);

var_dump($user); // Bob
var_dump($user2); // Jon




