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
