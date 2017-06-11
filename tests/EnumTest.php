<?php

use PHPUnit\Framework\TestCase;
use fpoirotte\EnumTrait;

final class Colors implements Serializable
{
    use EnumTrait;

    private $RED;
    private $BLUE;
    private $GREEN;
}

final class OldColors
{
    use EnumTrait;

    private $RED;
    private $BLUE;
    private $GREEN;
}

class EnumTest extends TestCase
{
    public function testInstanciation()
    {
        $this->assertInternalType('object', Colors::RED());
    }

    public function testIdentity()
    {
        $red1 = Colors::RED();
        $red2 = Colors::RED();
        $blue = Colors::BLUE();
        $red0 = Colors::RED(true);

        // By default, instances are cached, so the two instances
        // are actually identical.
        $this->assertSame($red1, $red2);
        $this->assertEquals($red1, $red2);
        $this->assertNotEquals($red1, $blue);

        // In contrast, when the cache is disabled, the two instances
        // will be equal, but not identical.
        $this->assertNotSame($red1, $red0);
        $this->assertEquals($red1, $red0);
    }

    public function testCloning()
    {
        $red1 = Colors::RED();
        $red2 = clone $red1;

        // Cloning produces equal (but unidentical) copies.
        $this->assertEquals($red1, $red2);
        $this->assertNotSame($red1, $red2);
    }

    public function testOldSerialization()
    {
        $red1   = OldColors::RED();
        $red2   = unserialize(serialize($red1));

        // Serialization produces equal (but unidentical) copies.
        $this->assertEquals($red1, $red2);
        $this->assertNotSame($red1, $red2);
    }

    public function testNewSerialization()
    {
        $red1   = Colors::RED();
        $red2   = unserialize(serialize($red1));

        // Serialization produces equal (but unidentical) copies.
        $this->assertEquals($red1, $red2);
        $this->assertNotSame($red1, $red2);
    }

    public function testInvalidValues()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid value for enum: YELLOW');
        Colors::YELLOW();
    }

    public function testStringConversion()
    {
        $this->assertEquals("RED", (string) Colors::RED());
    }
}
