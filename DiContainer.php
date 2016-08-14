<?php

include ('Container.php');

class DiContainer implements Container
{
    private $objectTypes = [];
    private $childObjects;

    public static function getInstance()
    {
        return new static();
    }
    private function __construct()
    {
    }

    private static $class;

    public function get($class)
    {
        if(!is_null(self::$class)) {
            return $this->class;
        }
        return false;
    }

    private function getChildren($class)
    {
        if(!class_exists($class)){
            return;
        }
        $reflection = new ReflectionClass($class);
        if (null === $reflection->getConstructor()){
            return;
        }
        foreach ($reflection->getConstructor()->getParameters() as $parameter) {
            if (null !== $parameter->getClass()){
                $str =  $parameter->getClass()->getName();
                $this->objectTypes[] = $str;
                $this->getChildren($str);
            }
        }
    }

    public function create($class, array $arguments = [])
    {
        if(is_null(self::$class)) {
            $this->getChildren($class);
            foreach (array_reverse($this->objectTypes) as $obj)
            {
                if (null === $this->childObjects) {
                    $this->childObjects = new $obj;
                } else {
                    $this->childObjects = new $obj($this->childObjects);
                }
            }

            return new $class($this->childObjects, $arguments['name'], $arguments['test']);
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

    public function __construct(Jon $bob, $name = 'guest')
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

    public function __construct(Bob $sub, $name, $test)
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




