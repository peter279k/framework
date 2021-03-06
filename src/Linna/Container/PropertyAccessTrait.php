<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Container;

/**
 * Magic Access Trait
 * Provide to DIContainer the possibility to retrive values using properties.
 */
trait PropertyAccessTrait
{
    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key
     */
    abstract public function has($key);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key
     */
    abstract public function get($key);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key
     * @param mixed  $value
     */
    abstract public function set($key, $value);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key
     */
    abstract public function delete($key): bool;

    /**
     * Set
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @ignore
     */
    public function __set(string $key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Get
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $key
     *
     * @return object|bool Element stored in container or false
     *
     * @ignore
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * Remove
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $key
     *
     * @ignore
     */
    public function __unset(string $key)
    {
        $this->delete($key);
    }

    /**
     * Check
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $key
     *
     * @return bool
     *
     * @ignore
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }
}
