<?php

namespace fphammerle\helpers\tests;

class TestClass
{
    use \fphammerle\helpers\PropertyAccessTrait;

    private $_a;

    public function __construct($a)
    {
        $this->_a = $a;
    }

    public function getA()
    {
        return $this->_a;
    }

    public function setA($value)
    {
        $this->_a = $value;
    }

    public function getSquare()
    {
        return $this->_a * $this->_a;
    }

    public function setCubic($c)
    {
        $this->_a = pow($c, 1/3);
    }
}

class PropertyAccessTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPublic()
    {
        $o = new TestClass(2);
        $this->assertEquals(2, $o->a);
    }

    public function testGetPublic2()
    {
        $o = new TestClass(3);
        $this->assertEquals(9, $o->square);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetUnknown()
    {
        $o = new TestClass(2);
        $o->cubic;
    }

    public function testSetPublic()
    {
        $o = new TestClass(1);
        $o->a = 2;
        $this->assertEquals(2, $o->getA());
    }

    public function testSetPublic2()
    {
        $o = new TestClass(3);
        $o->cubic = 8;
        $this->assertEquals(2, $o->getA(), '', 0.1);
    }

    /**
     * @expectedException \Exception
     */
    public function testSetUnknown()
    {
        $o = new TestClass(1);
        $o->square = 4;
    }

    public function testIncrement()
    {
        $o = new TestClass(1);
        $o->a++;
        $this->assertEquals(2, $o->getA());
    }

    public function testAdd()
    {
        $o = new TestClass(1);
        $o->a += 3;
        $this->assertEquals(4, $o->getA());
    }

    public function testIssetTrue()
    {
        $o = new TestClass(2);
        $this->assertTrue(isset($o->a));
    }

    public function testIssetEmpty()
    {
        $o = new TestClass('');
        $this->assertEquals('', $o->a);
        $this->assertTrue(isset($o->a));
    }

    public function testIssetNull()
    {
        $o = new TestClass(null);
        $this->assertFalse(isset($o->a));
    }

    public function testIssetUndefined()
    {
        $o = new TestClass(null);
        $this->assertFalse(isset($o->b));
    }
}
