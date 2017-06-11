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

final class OldSerializableColors
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

        $otherRed = OldSerializableColors::RED();

        // By default, instances are cached, so the two instances
        // are actually identical.
        $this->assertSame($red1, $red2);
        $this->assertEquals($red1, $red2);
        $this->assertNotEquals($red1, $blue);

        // In contrast, when the cache is disabled, the two instances
        // will be equal, but not identical.
        $this->assertNotSame($red1, $red0);
        $this->assertEquals($red1, $red0);

        // Different classes result in different enums,
        // even if they define the same labels.
        $this->assertNotEquals($red1, $otherRed);
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
        $red1   = OldSerializableColors::RED();
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

    /**
     * @expectedException           InvalidArgumentException
     * @expectedExceptionMessage    Invalid value for enum Colors: YELLOW
     */
    public function testInvalidValues()
    {
        Colors::YELLOW();
    }

    public function testStringConversion()
    {
        $this->assertEquals("RED", (string) Colors::RED());
    }

    public function testExportAndImport()
    {
        $red1   = Colors::RED();
        $dump   = var_export($red1, true);
        $red2   = eval("return $dump;");

        // Exporting a value produces an equal copy.
        // Depending on whether the trait was already loaded or not,
        // this may also produce an exact copy.
        $this->assertEquals($red1, $red2);
    }
}
