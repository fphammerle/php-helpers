<?php

namespace fphammerle\helpers\tests;

use fphammerle\helpers\HtmlHelper;

class HtmlHelperTest extends \PHPUnit_Framework_TestCase
{
    public function encodeProvider()
    {
        return [
            ['abc', 'abc'],
            ['可以', '可以'],
            ['⚕', '⚕'],
            ['<abc>', '&lt;abc&gt;'],
            ['alert(":-)");', 'alert(&quot;:-)&quot;);'],
            ['alert(\':-)\');', 'alert(&#039;:-)&#039;);'],
            ];
    }

    /**
     * @dataProvider encodeProvider
     */
    public function testEncode($string, $expected)
    {
        $this->assertSame($expected, HtmlHelper::encode($string));
    }

    public function voidTagTypeErrorProvider()
    {
        return [
            [1, []],
            [false, []],
            ['tag', true],
            ['tag', 'attr'],
            ];
    }

    /**
     * @dataProvider voidTagTypeErrorProvider
     * @expectedException \TypeError
     */
    public function testVoidTagTypeError($name, $attributes)
    {
        HtmlHelper::voidTag($name, $attributes);
    }

    public function voidTagProvider()
    {
        return [
            ['tag', [], '<tag />'],
            ['void', ['a' => '1'], '<void a="1" />'],
            ['void', ['a' => ''], '<void a="" />'],
            ['void', ['a' => 1], '<void a="1" />'],
            ['void', ['a' => '1', 'b' => '2'], '<void a="1" b="2" />'],
            ['void', ['b' => '1', 'a' => '2'], '<void b="1" a="2" />'],
            ['void', ['a' => null, 'b' => '2'], '<void b="2" />'],
            ['void', ['a' => true], '<void a="a" />'],
            ['void', ['a' => true, 'b' => '2'], '<void a="a" b="2" />'],
            ['void', ['a' => false], '<void />'],
            ['void', ['a' => false, 'b' => '2'], '<void b="2" />'],
            ['script', ['type' => 'text/javascript'], '<script type="text/javascript" />'],
            ['img', ['ondrag' => 'alert(":-)")'], '<img ondrag="alert(&quot;:-)&quot;)" />'],
            ['img', ['ondrag' => "alert(':-)')"], '<img ondrag="alert(&#039;:-)&#039;)" />'],
            [null, [], null],
            [null, ['attr' => 'v'], null],
            ];
    }

    /**
     * @dataProvider voidTagProvider
     */
    public function testVoidTag($name, $attributes, $expected_tag)
    {
        $this->assertSame($expected_tag, HtmlHelper::voidTag($name, $attributes));
    }

    public function testVoidTagNoAttributes()
    {
        $this->assertSame('<tag />', HtmlHelper::voidTag('tag'));
    }

    public function startTagTypeErrorProvider()
    {
        return [
            [1, []],
            [false, []],
            ['tag', true],
            ['tag', 'attr'],
            ];
    }

    /**
     * @dataProvider startTagTypeErrorProvider
     * @expectedException \TypeError
     */
    public function testStartTagTypeError($name, $attributes)
    {
        HtmlHelper::startTag($name, $attributes);
    }

    public function startTagProvider()
    {
        return [
            ['tag', [], '<tag>'],
            ['start', ['a' => '1'], '<start a="1">'],
            ['start', ['a' => ''], '<start a="">'],
            ['start', ['a' => 1], '<start a="1">'],
            ['start', ['a' => '1', 'b' => '2'], '<start a="1" b="2">'],
            ['start', ['b' => '1', 'a' => '2'], '<start b="1" a="2">'],
            ['start', ['a' => null, 'b' => '2'], '<start b="2">'],
            ['start', ['a' => true], '<start a="a">'],
            ['start', ['a' => true, 'b' => '2'], '<start a="a" b="2">'],
            ['start', ['a' => false], '<start>'],
            ['start', ['a' => false, 'b' => '2'], '<start b="2">'],
            ['script', ['type' => 'text/javascript'], '<script type="text/javascript">'],
            ['span', ['onclick' => 'alert(":-)")'], '<span onclick="alert(&quot;:-)&quot;)">'],
            ['span', ['onclick' => "alert(':-)')"], '<span onclick="alert(&#039;:-)&#039;)">'],
            [null, [], null],
            [null, ['attr' => 'v'], null],
            ];
    }

    /**
     * @dataProvider startTagProvider
     */
    public function testStartTag($name, $attributes, $expected_tag)
    {
        $this->assertSame($expected_tag, HtmlHelper::startTag($name, $attributes));
    }

    public function testStartTagNoAttributes()
    {
        $this->assertSame('<tag>', HtmlHelper::startTag('tag'));
    }

    public function endTagTypeErrorProvider()
    {
        return [
            [1],
            [false],
            ];
    }

    /**
     * @dataProvider endTagTypeErrorProvider
     * @expectedException \TypeError
     */
    public function testEndTagTypeError($name)
    {
        HtmlHelper::endTag($name);
    }

    public function endTagProvider()
    {
        return [
            ['tag', '</tag>'],
            ['end', '</end>'],
            [null, null],
            ];
    }

    /**
     * @dataProvider endTagProvider
     */
    public function testEndTag($name, $expected_tag)
    {
        $this->assertSame($expected_tag, HtmlHelper::endTag($name));
    }

    public function nonVoidTagProvider()
    {
        return [
            [null, null, [], null],
            [null, null, ['a' => '1'], null],
            [null, 'content', [], 'content'],
            [null, 'content', ['a' => '1'], 'content'],
            [null, 'qu"ote', [], 'qu"ote'],
            [null, '', [], ''],
            ['tag', null, [], null],
            ['tag', null, ['a' => true, 'b' => 2], null],
            ['tag', '', [], '<tag></tag>'],
            ['tag', '', ['a' => true, 'b' => 2], '<tag a="a" b="2"></tag>'],
            ['tag', 'content', [], '<tag>content</tag>'],
            ['tag', 'content', ['a' => true, 'b' => 2], '<tag a="a" b="2">content</tag>'],
            ['tag', 'content', ['a' => null], '<tag>content</tag>'],
            ['tag', 'content', ['a' => 'qu"ote'], '<tag a="qu&quot;ote">content</tag>'],
            ['tag', 'cont"ent', ['a' => ''], '<tag a="">cont"ent</tag>'],
            ];
    }

    /**
     * @dataProvider nonVoidTagProvider
     */
    public function testNonVoidTag($name, $content, $attributes, $expected_tag)
    {
        $this->assertSame($expected_tag, HtmlHelper::nonVoidTag($name, $content, $attributes));
    }
}
