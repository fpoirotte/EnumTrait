<?php

namespace fpoirotte;

trait EnumTrait
{
    final private function __construct($value)
    {
        $this->unserialize($value);
    }

    final public static function __callStatic($value, $nocache)
    {
        static $values = array();

        $dynClass   = get_called_class();
        $reflector  = new \ReflectionClass($dynClass);

        try {
            $property = $reflector->getProperty($value);
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException("Invalid value for enum $dynClass: $value");
        }
        $defClass   = $property->getDeclaringClass()->getName();

        // Find all parents between the called class and the defining class.
        $parents    = array($dynClass);
        for ($parent = $dynClass; false !== $parent && $parent !== $defClass; $parent = get_parent_class($parent)) {
            $parents[] = $parent;
        }
        $parents = array_reverse($parents);

        // Now, locate the first non-abstract class between the two,
        // starting from the defining class.
        foreach ($parents as $parent) {
            $reflector  = new \ReflectionClass($parent);
            if (!$reflector->isAbstract()) {
                break;
            }
        }

        if ($nocache || !isset($values[$parent][$value])) {
            $res = new $parent($value);
            if (!$nocache) {
                $values[$parent][$value] = $res;
            }
        } else {
            $res = $values[$parent][$value];
        }

        return $res;
    }

    final public static function value($value)
    {
        $args = func_get_args();
        array_shift($args);
        return self::__callStatic($value, $args);
    }

    final public function __sleep()
    {
        if (!isset($this)) {
            $args = func_get_args();
            return self::__callStatic(__FUNCTION__, $args);
        }

        return array("!\x00");
    }

    final public function __wakeup()
    {
        if (!isset($this)) {
            $args = func_get_args();
            return self::__callStatic(__FUNCTION__, $args);
        }

        foreach (get_object_vars($this) as $var => $dummy) {
            if ("!\x00" !== $var) {
                unset($this->$var);
            }
        }
    }

    final public function serialize()
    {
        if (!isset($this)) {
            $args = func_get_args();
            return self::__callStatic(__FUNCTION__, $args);
        }

        return $this->{"!\x00"};
    }

    final public function unserialize($value)
    {
        if (!isset($this)) {
            $args = func_get_args();
            return self::__callStatic(__FUNCTION__, $args);
        }

        foreach (get_object_vars($this) as $var => $dummy) {
            unset($this->$var);
        }
        $this->{"!\x00"} = $value;
    }

    final public function __clone()
    {
        if (!isset($this)) {
            $args = func_get_args();
            return self::__callStatic(__FUNCTION__, $args);
        }
    }

    public function __toString()
    {
        if (!isset($this)) {
            $args = func_get_args();
            return self::__callStatic(__FUNCTION__, $args);
        }

        return $this->{"!\x00"};
    }

    public static function __set_state($properties)
    {
        if (!isset($properties["!\x00"]) && !isset($properties["!"])) {
            throw new \InvalidArgumentException('Invalid properties');
        }

        if (isset($properties["!\x00"])) {
            return self::__callStatic($properties["!\x00"], false);
        } else {
            // Older versions of PHP (5.x) are not binary-safe when calling
            // __set_state() and will thus strip the trailing NUL byte.
            return self::__callStatic($properties["!"], false);
        }
    }
}
