<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Utility;

use Titon\Test\TestCase;
use Titon\Utility\Path;
use \Exception;

/**
 * Test class for Titon\Utility\Path.
 */
class PathTest extends TestCase {

    /**
     * Test that certain file paths are replaced with constant shortcuts.
     */
    public function testAlias() {
        $this->assertEquals('[internal]', Path::alias(null));
        $this->assertEquals('[vendor]Titon' . DS . 'Debug' . DS . 'Debugger.php', Path::alias(VENDOR_DIR . '/Titon/Debug/Debugger.php'));
        $this->assertEquals('[src]Titon' . DS . 'Debug' . DS . 'Debugger.php', Path::alias(dirname(TEST_DIR) . '/src/Titon/Debug/Debugger.php'));
        $this->assertEquals('[app]some' . DS . 'file.txt', Path::alias('/app/some/file.txt', array('app' => '/app')));
    }

    /**
     * Test that the class name is returned without the namespace or extension.
     */
    public function testClassName() {
        $this->assertEquals('ClassName', Path::className('\test\namespace\ClassName'));
        $this->assertEquals('ClassName', Path::className('test:namespace:ClassName', ':'));
        $this->assertEquals('ClassName', Path::className('test/namespace/ClassName', '/'));
        $this->assertEquals('ClassName', Path::className('test.namespace.ClassName', '.'));
    }

    /**
     * Test that only the namespace package is returned when a fully qualified class name is returned.
     */
    public function testPackageName() {
        $this->assertEquals('test\namespace', Path::packageName('\test\namespace\ClassName'));
        $this->assertEquals('test/namespace', Path::packageName('/test/namespace/ClassName', '/'));
    }

    /**
     * Test that all slashes are converted to forward slashes (works for linux and windows).
     */
    public function testDs() {
        // linux
        $this->assertEquals(DS . 'some' . DS . 'fake' . DS . 'folder' . DS . 'path' . DS . 'fileName.php', Path::ds('/some/fake/folder/path/fileName.php'));
        $this->assertEquals(DS . 'some' . DS . 'fake' . DS . 'folder' . DS . 'path' . DS . 'fileName.php', Path::ds('/some\fake/folder\path/fileName.php'));

        // windows
        $this->assertEquals('C:' . DS . 'some' . DS . 'fake' . DS . 'folder' . DS . 'path' . DS . 'fileName.php', Path::ds('C:\some\fake\folder\path\fileName.php'));
        $this->assertEquals('C:' . DS . 'some' . DS . 'fake' . DS . 'folder' . DS . 'path' . DS . 'fileName.php', Path::ds('C:\some/fake\folder/path\fileName.php'));

        // linux
        $this->assertEquals(DS . 'some' . DS . 'fake' . DS . 'folder' . DS . 'path' . DS . 'fileName' . DS, Path::ds('/some/fake/folder/path/fileName', true));
        $this->assertEquals(DS . 'some' . DS . 'fake' . DS . 'folder' . DS . 'path' . DS . 'fileName' . DS, Path::ds('/some\fake/folder\path/fileName/', true));

        // windows
        $this->assertEquals('C:' . DS . 'some' . DS . 'fake' . DS . 'folder' . DS . 'path' . DS . 'fileName' . DS, Path::ds('C:\some\fake\folder\path\fileName/'));
        $this->assertEquals('C:' . DS . 'some' . DS . 'fake' . DS . 'folder' . DS . 'path' . DS . 'fileName' . DS, Path::ds('C:\some/fake\folder/path\fileName\\'));
    }

    /**
     * Test that defining new include paths registers correctly.
     */
    public function testIncludePath() {
        $baseIncludePath = get_include_path();
        $selfPath1 = '/fake/test/1';
        $selfPath2 = '/fake/test/2';
        $selfPath3 = '/fake/test/3';

        $this->assertEquals($baseIncludePath, get_include_path());

        Path::includePath($selfPath1);
        $this->assertEquals($baseIncludePath . PATH_SEPARATOR . $selfPath1, get_include_path());

        Path::includePath(array($selfPath2, $selfPath3));
        $this->assertEquals($baseIncludePath . PATH_SEPARATOR . $selfPath1 . PATH_SEPARATOR . $selfPath2 . PATH_SEPARATOR . $selfPath3, get_include_path());
    }

    /**
     * Test absolute path detection.
     */
    public function testIsAbsolute() {
        $this->assertTrue(Path::isAbsolute('/root/path'));
        $this->assertTrue(Path::isAbsolute('\root\path'));
        $this->assertTrue(Path::isAbsolute('C:\root\path'));
        $this->assertTrue(Path::isAbsolute('abc123:\root\path'));
        $this->assertFalse(Path::isAbsolute('sub/'));
        $this->assertFalse(Path::isAbsolute('./sub/'));
        $this->assertFalse(Path::isAbsolute('../sub/'));
    }

    /**
     * Test relative path detection.
     */
    public function testIsRelative() {
        $this->assertFalse(Path::isRelative('/root/path'));
        $this->assertFalse(Path::isRelative('\root\path'));
        $this->assertFalse(Path::isRelative('C:\root\path'));
        $this->assertFalse(Path::isRelative('abc123:\root\path'));
        $this->assertTrue(Path::isRelative('sub/'));
        $this->assertTrue(Path::isRelative('./sub/'));
        $this->assertTrue(Path::isRelative('../sub/'));
    }

    /**
     * Test that multiple path parts join correctly and resolve "." and "..".
     */
    public function testJoin() {
        $this->assertEquals('foo' . DS . 'bar', Path::join(array('foo', 'bar')));
        $this->assertEquals('foo' . DS . 'bar', Path::join(array('foo/', '/bar/')));
        $this->assertEquals('foo' . DS . 'baz', Path::join(array('foo/', '/bar/', '..', '//baz')));
        $this->assertEquals('baz', Path::join(array('foo/', '/bar/', '..', '..', '//baz')));
        $this->assertEquals('..' . DS . 'baz', Path::join(array('foo/', '..', '/bar', '.', '..', '..', '//baz')));
        $this->assertEquals('baz', Path::join(array('foo/', '..', '/bar', '.', '..', '..', '//baz'), false));
        $this->assertEquals('foo' . DS . 'bar' . DS . 'foo' . DS . 'a' . DS . 'b' . DS . 'c' . DS . 'e', Path::join(array('foo', '.', 'bar\\baz', '..', 'foo', '.', 'a/b/c', 'd/../e')));
        $this->assertEquals(array('foo', 'baz'), Path::join(array('foo/', '/bar/', '..', '//baz'), true, false));

        try {
            Path::join(array('foo', 123));
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test that a relative path is generated from 2 absolute paths.
     */
    public function testRelativeTo() {
        $this->assertEquals('.' . DS, Path::relativeTo('/foo/bar', '/foo/bar'));
        $this->assertEquals('..' . DS, Path::relativeTo('/foo/bar', '/foo'));
        $this->assertEquals('.' . DS . 'baz' . DS, Path::relativeTo('/foo/bar', '/foo/bar/baz'));
        $this->assertEquals('..' . DS . '..' . DS . '..' . DS . '..' . DS . 'd' . DS . 'e' . DS . 'f' . DS, Path::relativeTo('/foo/bar/a/b/c', '/foo/d/e/f'));

        try {
            Path::relativeTo('/abs', '../rel');
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test that removing an extension from a file path works correctly.
     */
    public function testStripExt() {
        $this->assertEquals('NoExt', Path::stripExt('NoExt'));
        $this->assertEquals('ClassName', Path::stripExt('ClassName.php'));
        $this->assertEquals('File_Name', Path::stripExt('File_Name.php'));

        $this->assertEquals('\test\namespace\ClassName', Path::stripExt('\test\namespace\ClassName.php'));
        $this->assertEquals('\test\namespace\Class_Name', Path::stripExt('\test\namespace\Class_Name.php'));

        $this->assertEquals('/test/file/path/FileName', Path::stripExt('/test/file/path/FileName.php'));
        $this->assertEquals('/test/file/path/File/Name', Path::stripExt('/test/file/path/File/Name.php'));
    }

    /**
     * Test that converting a path to a namespace package works correctly.
     */
    public function testToNamespace() {
        $this->assertEquals('test\file\path\FileName', Path::toNamespace('/test/file/path/FileName.php'));
        $this->assertEquals('test\file\path\File\Name', Path::toNamespace('/test/file/path/File/Name.php'));

        $this->assertEquals('test\file\path\FileName', Path::toNamespace('vendors/src/test/file/path/FileName.php'));
        $this->assertEquals('Titon\test\file\path\File\Name', Path::toNamespace('vendors/src/Titon/test/file/path/File/Name.php'));
    }

    /**
     * Test that converting a namespace to a path works correctly.
     */
    public function testToPath() {
        $this->assertEquals(DS . 'test' . DS . 'namespace' . DS . 'ClassName.php', Path::toPath('\test\namespace\ClassName'));
        $this->assertEquals(DS . 'test' . DS . 'namespace' . DS . 'Class' . DS . 'Name.php', Path::toPath('\test\namespace\Class_Name'));

        $this->assertEquals(DS . 'Test' . DS . 'NameSpace' . DS . 'ClassName.php', Path::toPath('\Test\NameSpace\ClassName'));
        $this->assertEquals(DS . 'Test' . DS . 'NameSpace' . DS . 'Class' . DS . 'Name.php', Path::toPath('\Test\NameSpace\Class_Name'));

        $this->assertEquals(DS . 'test' . DS . 'namespace' . DS . 'ClassName.PHP', Path::toPath('\test\namespace\ClassName', 'PHP'));
        $this->assertEquals(DS . 'test' . DS . 'namespace' . DS . 'Class' . DS . 'Name.PHP', Path::toPath('\test\namespace\Class_Name', 'PHP'));

        $this->assertEquals(TEST_DIR . DS . 'test' . DS . 'namespace' . DS . 'ClassName.php', Path::toPath('\test\namespace\ClassName', 'php', TEST_DIR));
        $this->assertEquals(TEST_DIR . DS . 'test' . DS . 'namespace' . DS . 'Class' . DS . 'Name.php', Path::toPath('\test\namespace\Class_Name', 'php', TEST_DIR));

        $this->assertEquals(VENDOR_DIR . DS . 'test' . DS . 'namespace' . DS . 'ClassName.php', Path::toPath('\test\namespace\ClassName', 'php', VENDOR_DIR));
        $this->assertEquals(VENDOR_DIR . DS . 'test' . DS . 'namespace' . DS . 'Class' . DS . 'Name.php', Path::toPath('\test\namespace\Class_Name', 'php', VENDOR_DIR));
    }

}