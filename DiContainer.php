<?php

include ('Container.php');

class DiContainer implements Container
{
    private static $instance;
    private $variablesNames;
    private $mainArgs;
    private $argsArray;
    private $objectTypes;
    private $objects;
    private $childObjects;

    public function get($class)
    {
        if(is_null(self::$instance)) {
            $this->create($class);
        }
        return self::$instance;
    }

    private function getSignature($class)
    {
        if(!class_exists($class)){
            return;
        }
        $reflection = new ReflectionClass($class);
        if (null === $reflection->getConstructor()){
            return;
        }
        if (null === $this->mainArgs) {
            $this->mainArgs = $reflection->getConstructor()->getParameters();
        }
        foreach ($reflection->getConstructor()->getParameters() as $parameter) {
            if (null !== $parameter->getClass()){
                $str = $parameter->getClass()->getName();
                $this->objectTypes[] = $str;
                $this->getSignature($str);
                continue;
            }
            $this->variablesNames[] = $parameter->getName();
        }
    }

    public function create($class, array $arguments = [])
    {
        if(!is_null(self::$instance)) {
            return self::$instance;
        }

        $this->getSignature($class);
        foreach (array_reverse($this->objectTypes) as $obj)
        {
            if (null === $this->childObjects) {
                $this->childObjects = new $obj;
            } else {
                $this->childObjects = new $obj($this->childObjects);
            }
        }

        if(!is_null(static::$instance)) {
            return self::$instance;
        }

        $this->objects[] = $this->childObjects;
        $objectIndex = 0;
        $variableIndex = 0;
        foreach ($this->mainArgs as $arg) {
            /** $var ReflectionParameter $arg*/
           if ($arg->getClass()) {
               $this->argsArray[] = $this->objects[$objectIndex++];
               continue;
           }
           $this->argsArray[] = $this->variablesNames[$variableIndex++];
        }

        $newClass = new ReflectionClass($class);
        $shared = $newClass->newInstanceArgs($this->argsArray);
        return self::$instance = $shared;
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

    public function __construct($name, Bob $sub, $test)
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

class Jon {}

$diContainer = new DiContainer;

$user = $diContainer->create(TestUser::class, ['test' => 123, 'name' => 'Bob']);
$user2 = $diContainer->create(TestUser::class, ['test' => 888, 'name' => 'Jon']);

var_dump($user);
var_dump($user2);




