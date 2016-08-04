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
}
