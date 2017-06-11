<?php

use PHPUnit\Framework\TestCase;
use fpoirotte\EnumTrait;

abstract class Monochrome implements Serializable
{
    use EnumTrait;

    protected $BLACK;
    protected $WHITE;
}

class Grayscale extends Monochrome
{
    protected $LIGHTGRAY;
    protected $DARKGRAY;
}

final class BasicColors extends Grayscale
{
    protected $RED;
    protected $BLUE;
    protected $GREEN;
}

final class BasicColors2 extends Grayscale
{
    protected $BLUE;
    protected $WHITE;
    protected $RED;
}

class InheritanceTest extends TestCase
{
    public function testInheritedValues()
    {
        $black1 = Grayscale::BLACK();
        $black2 = BasicColors::BLACK();
        $this->assertEquals($black1, $black2);
    }

    public function testInheritedValues2()
    {
        $red1 = BasicColors::RED();
        $red2 = BasicColors2::RED();
        $this->assertNotEquals($red1, $red2);
    }

    /**
     * @expectedException           InvalidArgumentException
     * @expectedExceptionMessage    Invalid value for enum BasicColors: ORANGE
     */
    public function testInheritedException()
    {
        BasicColors::ORANGE();
    }
}
