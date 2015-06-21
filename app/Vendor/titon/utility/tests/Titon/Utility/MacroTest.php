<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Utility;

use Titon\Test\TestCase;
use Titon\Utility\String;
use \Exception;

/**
 * Test class for Titon\Utility\Macro.
 */
class MacroTest extends TestCase {

    /**
     * Test hasMethod() returns true for macros and methods.
     */
    public function testHasMacro() {
        $this->assertFalse(Number::hasMacro('toBinary'));
        $this->assertFalse(Number::hasMacro('toFloat'));

        Number::macro('toFloat', function() {});

        $this->assertFalse(Number::hasMacro('toBinary'));
        $this->assertTrue(Number::hasMacro('toFloat'));
    }

    /**
     * Test hasMethod() returns true for macros and methods.
     */
    public function testHasMethod() {
        $this->assertTrue(Inflector::hasMethod('slug'));
        $this->assertFalse(Inflector::hasMethod('slugify'));

        Inflector::macro('slugify', function() {});

        $this->assertTrue(Inflector::hasMethod('slug'));
        $this->assertTrue(Inflector::hasMethod('slugify'));
    }

    /**
     * Test that macros aren't shared between classes.
     */
    public function testInheritance() {
        $this->assertFalse(Format::hasMethod('foobar'));
        $this->assertFalse(Path::hasMethod('foobar'));

        Format::macro('foobar', function() {});

        $this->assertTrue(Format::hasMethod('foobar'));
        $this->assertFalse(Path::hasMethod('foobar'));
    }

    /**
     * Test that macros can be defined and triggered.
     */
    public function testMacro() {
        Inflector::macro('caps', function($value) {
            return strtoupper($value);
        });

        $this->assertEquals('FOOBAR', Inflector::caps('foObAr'));

        try {
            Inflector::lowers('foObAr');
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test all macros are returned.
     */
    public function testMacros() {
        $lower = function($value) {
            return strtolower($value);
        };

        $upper = function($value) {
            return strtoupper($value);
        };

        String::macro('lower', $lower);
        String::macro('upper', $upper);

        $this->assertEquals(array(
            'lower' => $lower,
            'upper' => $upper
        ), String::macros());
    }

}