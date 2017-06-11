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

        if ($nocache || !isset($values[$value])) {
            $reflector  = new \ReflectionClass(__CLASS__);

            if (!$reflector->hasProperty($value)) {
                return null;
            }

            $res = new self($value);
            if (!$nocache) {
                $values[$value] = $res;
            }
        } else {
            $res = $values[$value];
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

    final public function getName()
    {
        if (!isset($this)) {
            $args = func_get_args();
            return self::__callStatic(__FUNCTION__, $args);
        }
        return $this->{"!\x00"};
    }
}
