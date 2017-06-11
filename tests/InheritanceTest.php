<?php

use PHPUnit\Framework\TestCase;
use fpoirotte\EnumTrait;

class Monochrome implements Serializable
{
    use EnumTrait;

    protected $BLACK;
    protected $WHITE;
}

final class Grayscale extends Monochrome
{
    protected $LIGHTGRAY;
    protected $DARKGRAY;
}

class InheritanceTest extends TestCase
{
    public function testInheritedValues()
    {
        $black1 = Monochrome::BLACK();
        $black2 = Grayscale::BLACK();
        $this->assertEquals($black1, $black2);
    }

    /**
     * @expectedException           InvalidArgumentException
     * @expectedExceptionMessage    Invalid value for enum Grayscale: ORANGE
     */
    public function testInheritedException()
    {
        Grayscale::ORANGE();
    }
}
