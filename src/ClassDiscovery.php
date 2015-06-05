<?php

namespace Http\Discovery;

/**
 * Registry that based find results on class existence
 *
 * @author David de Boer <david@ddeboer.nl>
 */
abstract class ClassDiscovery
{
    /**
     * Add a condition (and class) to the discovery registry
     *
     * @param string $name
     * @param string $class     Class that will be instantiated if found
     * @param string $condition Optional other class to check for existence
     */
    public static function register($name, $class, $condition = null)
    {
        static::$cache = null;

        static::$classes[$name] = [
            'class' => $class,
            'condition' => $condition ?: $class,
        ];
    }

    /**
     * Finds a Factory
     *
     * @return object
     *
     * @throws NotFoundException
     */
    public static function find()
    {
        // We have a cache
        if (isset(static::$cache)) {
            return static::$cache;
        }

        foreach (static::$classes as $name => $definition) {
            if (class_exists($definition['condition'])) {
                return static::$cache = new $definition['class'];
            }
        }

        throw new NotFoundException('Not found');
    }
}