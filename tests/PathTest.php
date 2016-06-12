<?php

namespace fphammerle\helpers\tests;

use \fphammerle\helpers\Path;

class PathTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider pathProvider
     */
    public function testConstruct($path, $expected_dir_path, $expected_filename, $expected_extension)
    {
        $p = new Path($path);
        if(isset($expected_dir_path)) {
            $this->assertSame($expected_dir_path, $p->dirPath->path);
        } else {
            $this->assertNull($p->dirPath);
        }
        $this->assertSame($expected_filename, $p->filename);
        $this->assertSame($expected_extension, $p->extension);
    }

    /**
     * @dataProvider pathProvider
     */
    public function testToString($expected_path, $dir_path, $filename, $extension)
    {
        $p = new Path;
        $p->dirPath = $dir_path;
        $p->filename = $filename;
        $p->extension = $extension;
        $this->assertSame($expected_path ?: '', (string)$p);
    }

    public function getBasenameProvider()
    {
        return [
            ['.', null, '.'],
            ['..', null, '..'],
            ['file', 'php', 'file.php'],
            ['file', null, 'file'],
            [null, 'php', '.php'],
            [null, null, null],
            ];
    }

    /**
     * @dataProvider getBasenameProvider
     */
    public function testGetBasename($filename, $extension, $expected_basename)
    {
        $p = new Path;
        $p->filename = $filename;
        $p->extension = $extension;
        $this->assertSame($expected_basename, $p->basename);
    }

    public function setBasenameProvider()
    {
        return [
            ['.', '.', null],
            ['..', '..', null],
            ['..txt', '.', 'txt'],
            ['.php', null, 'php'],
            ['file', 'file', null],
            ['file.php', 'file', 'php'],
            [null, null, null],
            ];
    }

    /**
     * @dataProvider setBasenameProvider
     */
    public function testSetBasename($basename, $expected_filename, $expected_extension)
    {
        $p = new Path;
        $p->basename = $basename;
        $this->assertSame($expected_filename, $p->filename);
        $this->assertSame($expected_extension, $p->extension);
    }

    public function dirPathProvider()
    {
        return [
            [null, null],
            ];
    }

    /**
     * @dataProvider dirPathProvider
     */
    public function testDirPath($input_dir_path, $expected_dir_path)
    {
        $p = new Path;
        $p->dirPath = $input_dir_path;
        $this->assertSame($expected_dir_path, $p->dirPath);
    }

    public function extensionProvider()
    {
        return [
            ['.', '.'],
            ['.php', '.php'],
            ['file', 'file'],
            ['file.php', 'file.php'],
            [123, '123'],
            [null, null],
            ];
    }

    /**
     * @dataProvider extensionProvider
     */
    public function testExtension($input_extension, $expected_extension)
    {
        $p = new Path;
        $p->extension = $input_extension;
        $this->assertSame($expected_extension, $p->extension);
    }

    public function filenameProvider()
    {
        return [
            ['.', '.'],
            ['.php', '.php'],
            ['file', 'file'],
            ['file.php', 'file.php'],
            [123, '123'],
            [null, null],
            ];
    }

    /**
     * @dataProvider filenameProvider
     */
    public function testFilename($input_filename, $expected_filename)
    {
        $p = new Path;
        $p->filename = $input_filename;
        $this->assertSame($expected_filename, $p->filename);
    }

    public function pathProvider()
    {
        return [
            ['.', null, '.', null],
            ['..', null, '..', null],
            ['../../file.php', '../..', 'file', 'php'],
            ['../file.php', '..', 'file', 'php'],
            ['./file.php', '.', 'file', 'php'],
            ['dir/../file.php', 'dir/..', 'file', 'php'],
            ['dir/file.php', 'dir', 'file', 'php'],
            ['dir/subdir/file.php', 'dir/subdir', 'file', 'php'],
            ['/file.php', '/', 'file', 'php'],
            ['/dir/file.php', '/dir', 'file', 'php'],
            ['/', '/', null, null],
            ['file.php', null, 'file', 'php'],
            [null, null, null, null],
            ];
    }

    /**
     * @dataProvider pathProvider
     */
    public function testGetPath($expected_path, $dir_path, $filename, $extension)
    {
        $p = new Path;
        $p->dirPath = $dir_path;
        $p->filename = $filename;
        $p->extension = $extension;
        $this->assertSame($expected_path, $p->path);
    }

    /**
     * @dataProvider pathProvider
     */
    public function testSetPath($path, $expected_dir_path, $expected_filename, $expected_extension)
    {
        $p = new Path;
        $p->path = $path;
        if(isset($expected_dir_path)) {
            $this->assertSame($expected_dir_path, $p->dirPath->path);
        } else {
            $this->assertNull($p->dirPath);
        }
        $this->assertSame($expected_filename, $p->filename);
        $this->assertSame($expected_extension, $p->extension);
    }

    /**
     * @dataProvider pathProvider
     */
    public function testSetEqualsGet($path)
    {
        $p = new Path;
        $p->path = $path;
        $this->assertSame($path, $p->path);
    }

    public function isRootProvider()
    {
        return [
            ['.', false],
            ['..', false],
            ['../../file.php', false],
            ['../file.php', false],
            ['./file.php', false],
            ['/', true],
            ['//', false],
            ['/dir/file.php', false],
            ['/file.php', false],
            ['dir/../file.php', false],
            ['dir/file.php', false],
            ['dir/subdir/file.php', false],
            ['file.php', false],
            [null, false],
            ];
    }

    /**
     * @dataProvider isRootProvider
     */
    public function testIsRoot($path, $expected_result)
    {
        $p = new Path;
        $p->path = $path;
        $this->assertSame($expected_result, $p->isRoot());
    }

    public function reverseNameProvider()
    {
        return [
            ['.', '.'],
            ['..', '..'],
            ['../../file.php', '../../elif.php'],
            ['dir/subdir/file.html', 'dir/subdir/elif.lmth'],
            ['file.png', 'elif.gnp'],
            [null, null],
            ];
    }

    /**
     * @dataProvider reverseNameProvider
     */
    public function testReverseName($path, $expected_result)
    {
        $p = new Path($path);
        if(isset($p->filename)) {
            $p->filename = strrev($p->filename);
        }
        if(isset($p->extension)) {
            $p->extension = strrev($p->extension);
        }
        $this->assertSame($expected_result, $p->path);
    }
}
