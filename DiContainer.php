<?php

include ('Container.php');

class DiContainer
{
    private $variablesNames;
    private $mainArgs;
    private $argsArray;
    private $objectTypes;
    private $objects;
    private $childObjects;

    private function getSignature($class)
    {
        if(!class_exists($class)){
            throw new Exception('Class ' . $class . ' doesn`t exist');
        }
        $reflection = new ReflectionClass($class);
        if (null === $reflection->getConstructor()){
            return false;
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
        try {
            if(!$this->getSignature($class)) {
                return new $class;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        foreach (array_reverse($this->objectTypes) as $obj)
        {
            if (null === $this->childObjects) {
                $this->childObjects = new $obj;
            } else {
                $this->childObjects = new $obj($this->childObjects);
            }
        }

        $this->objects[] = $this->childObjects;
        $objectIndex = 0;
        $variableIndex = 0;
        foreach ($this->mainArgs as $arg) {
            /** @var ReflectionParameter $arg*/
           if ($arg->getClass()) {
               $this->argsArray[] = $this->objects[$objectIndex++];
               continue;
           }
           $this->argsArray[] = $arguments[$this->variablesNames[$variableIndex++]];
        }

        $newClass = new ReflectionClass($class);
        $object = $newClass->newInstanceArgs($this->argsArray);

        unset($this->mainArgs);
        unset($this->childObjects);
        unset($this->objects);
        unset($this->objectTypes);
        unset($this->argsArray);
        unset($this->variablesNames);

        return $object;
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

    public function getProperties()
    {
        return get_object_vars($this);
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

var_dump($user = $diContainer->create(Jon::class));

//$user = $diContainer->create(TestUser::class, ['test' => 123, 'name' => 'Bob']);
//$user2 = $diContainer->create(TestUser::class, ['test' => 888, 'name' => 'Jon']);

//var_dump($user->getProperties());
//var_dump($user2->getProperties());




