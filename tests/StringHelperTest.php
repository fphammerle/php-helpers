<?php

namespace fphammerle\helpers\tests;

use fphammerle\helpers\StringHelper;

class StringHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testPrepend()
    {
        $this->assertEquals(
            'prefixstring',
            StringHelper::prepend('prefix', 'string')
            );
    }

    public function testPrependToEmpty()
    {
        $this->assertEquals(
            'prefix',
            StringHelper::prepend('prefix', '')
            );
    }

    public function testPrependToNull()
    {
        $this->assertEquals(
            null,
            StringHelper::prepend('prefix', null)
            );
    }

    public function testPrependNull()
    {
        $this->assertEquals(
            'string',
            StringHelper::prepend(null, 'string')
            );
    }

    public function testPrependToList()
    {
        $this->assertEquals(
            ['prefix1', 'prefix2', 'prefix3'],
            StringHelper::prepend('prefix', ['1', '2', '3'])
            );
    }

    public function testPrependToDict()
    {
        $this->assertEquals(
            ['a' => 'prefix1', 'b' => 'prefix2', 'prefix3'],
            StringHelper::prepend('prefix', ['a' => '1', 'b' => '2', '3'])
            );
    }

    public function testPrependToDictWithNull()
    {
        $this->assertEquals(
            ['a' => 'prefix1', 'b' => null, 'prefix3'],
            StringHelper::prepend('prefix', ['a' => '1', 'b' => null, '3'])
            );
    }

    public function testAppend()
    {
        $this->assertEquals(
            'stringsuffix',
            StringHelper::append('string', 'suffix')
            );
    }

    public function testAppendToEmpty()
    {
        $this->assertEquals(
            'suffix',
            StringHelper::append('', 'suffix')
            );
    }

    public function testAppendToNull()
    {
        $this->assertEquals(
            null,
            StringHelper::append(null, 'suffix')
            );
    }

    public function testAppendNull()
    {
        $this->assertEquals(
            'string',
            StringHelper::append('string', null)
            );
    }

    public function testAppendToList()
    {
        $this->assertEquals(
            ['1postfix', '2postfix', '3postfix'],
            StringHelper::append(['1', '2', '3'], 'postfix')
            );
    }

    public function testAppendToDict()
    {
        $this->assertEquals(
            ['a' => '1postfix', 'b' => '2postfix', '3postfix'],
            StringHelper::append(['a' => '1', 'b' => '2', '3'], 'postfix')
            );
    }

    public function testAppendToDictWithNull()
    {
        $this->assertEquals(
            ['a' => '1postfix', 'b' => null, '3postfix'],
            StringHelper::append(['a' => '1', 'b' => null, '3'], 'postfix')
            );
    }

    public function testEmbed()
    {
        $this->assertEquals(
            'prefixstringsuffix',
            StringHelper::embed('prefix', 'string', 'suffix')
            );
    }

    public function testEmbedToEmpty()
    {
        $this->assertEquals(
            'prefixsuffix',
            StringHelper::embed('prefix', '', 'suffix')
            );
    }

    public function testEmbedToNull()
    {
        $this->assertEquals(
            null,
            StringHelper::embed('prefix', null, 'suffix')
            );
    }

    public function testEmbedNull()
    {
        $this->assertEquals(
            'string',
            StringHelper::embed(null, 'string', null)
            );
    }

    public function testEmbedToList()
    {
        $this->assertEquals(
            ['prefix1postfix', 'prefix2postfix', 'prefix3postfix'],
            StringHelper::embed('prefix', ['1', '2', '3'], 'postfix')
            );
    }

    public function testEmbedToDict()
    {
        $this->assertEquals(
            ['a' => 'prefix1postfix', 'b' => 'prefix2postfix', 'prefix3postfix'],
            StringHelper::embed('prefix', ['a' => '1', 'b' => '2', '3'], 'postfix')
            );
    }

    public function testEmbedToDictWithNull()
    {
        $this->assertEquals(
            ['a' => 'prefix1postfix', 'b' => null, 'prefix3postfix'],
            StringHelper::embed('prefix', ['a' => '1', 'b' => null, '3'], 'postfix')
            );
    }

    public function testEmbrace()
    {
        $this->assertEquals(
            'bracestringbrace',
            StringHelper::embrace('brace', 'string')
            );
    }

    public function testEmbraceToEmpty()
    {
        $this->assertEquals(
            'bracebrace',
            StringHelper::embrace('brace', '')
            );
    }

    public function testEmbraceToNull()
    {
        $this->assertEquals(
            null,
            StringHelper::embrace('brace', null)
            );
    }

    public function testEmbraceNull()
    {
        $this->assertEquals(
            'string',
            StringHelper::embrace(null, 'string')
            );
    }

    public function testEmbraceToList()
    {
        $this->assertEquals(
            ['brace1brace', 'brace2brace', 'brace3brace'],
            StringHelper::embrace('brace', ['1', '2', '3'])
            );
    }

    public function testEmbraceToDict()
    {
        $this->assertEquals(
            ['a' => 'brace1brace', 'b' => 'brace2brace', 'brace3brace'],
            StringHelper::embrace('brace', ['a' => '1', 'b' => '2', '3'])
            );
    }

    public function testEmbraceToDictWithNull()
    {
        $this->assertEquals(
            ['a' => 'brace1brace', 'b' => null, 'brace3brace'],
            StringHelper::embrace('brace', ['a' => '1', 'b' => null, '3'])
            );
    }

    public function implodeProvider()
    {
        return [
            [',', ['a', 'b', null, 'd'], 'a,b,d'],
            [',', ['a', 'b', '', 'd'], 'a,b,,d'],
            [',', ['a', 'b', 'c', 'd'], 'a,b,c,d'],
            [',', [null, null, null], null],
            [',', null, null],
            ['', ['a', '', 'c', 'd'], 'acd'],
            ['', ['a', 3, 'c', 'd'], 'a3cd'],
            ['', ['a', 'b', 'c', 'd'], 'abcd'],
            [null, ['a', 'b', 'c', 'd'], 'abcd'],
            ];
    }

    /**
     * @dataProvider implodeProvider
     */
    public function testImplode($glue, $pieces, $expected)
    {
        $this->assertSame($expected, StringHelper::implode($glue, $pieces));
    }

    public function containsAnyProvider()
    {
        return [
            [['a', 'b', 's'], 'string', true],
            [['a', 'b'], 'string', false],
            [['a'], '', false],
            [['a'], 'string', false],
            [['ng'], 'string', true],
            [['ngg', 'ri'], 'string', true],
            [['ngg'], 'string', false],
            [['sti', 'sri'], 'string', false],
            [['sti', 'str', 'sri'], 'string', true],
            [['str', 'sri'], 'string', true],
            [[], '', false],
            [[], 'string', false],
            ];
    }

    /**
     * @dataProvider containsAnyProvider
     */
    public function testContainsAny(array $needles, $haystack, $result)
    {
        $this->assertSame($result, StringHelper::containsAny($needles, $haystack));
    }

    public function containsAnyEmptyNeedleProvider()
    {
        return [
            [[''], ''],
            [[''], 'string'],
            [['a', '', 'c'], ''],
            [['a', '', 'c'], 'string'],
            ];
    }

    /**
     * @dataProvider containsAnyEmptyNeedleProvider
     * @expectedException InvalidArgumentException
     */
    public function testContainsAnyEmptyNeedle(array $needles, $haystack)
    {
        StringHelper::containsAny($needles, $haystack);
    }
}
