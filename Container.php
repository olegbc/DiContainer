<?php

interface Container
{
    /**
     * Get new instance of object
     *
     * @param string $class
     * @param array $arguments
     * @return object
     */
    public function create($class, array $arguments = []);

    /**
     * Get shared instance of object
     *
     * @param string $class
     * @return object
     */
    public function get($class);
}