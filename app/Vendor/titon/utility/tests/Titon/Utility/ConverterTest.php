<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Utility;

use Titon\Test\TestCase;
use Titon\Type\Contract\Arrayable;
use Titon\Type\Contract\Jsonable;
use Titon\Type\Contract\Xmlable;
use \Exception;
use \SimpleXMLElement;

/**
 * Test class for Titon\Utility\Converter.
 */
class ConverterTest extends TestCase {

    public $array;
    public $object;
    public $json;
    public $serialized;
    public $xml;
    public $barbarian;

    /**
     * Setup resources.
     */
    protected function setUp() {
        parent::setUp();

        $data = array('key' => 'value', 'number' => 1337, 'boolean' => true, 'float' => 1.50, 'array' => array(1, 2, 3));

        $this->array = $data;

        // Object
        $object = new \stdClass();
        $object->key = 'value';
        $object->number = 1337;
        $object->boolean = true;
        $object->float = 1.50;
        $subObject = new \stdClass();
        $subObject->{'0'} = 1;
        $subObject->{'1'} = 2;
        $subObject->{'2'} = 3;
        $object->array = $subObject;

        $this->object = $object;

        // Json
        $this->json = json_encode($data);

        // Serialized
        $this->serialized = serialize($data);

        // XML
        $xml  = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $xml .= '<root>';
        $xml .= '<key>value</key>';
        $xml .= '<number>1337</number>';
        $xml .= '<boolean>true</boolean>';
        $xml .= '<float>1.5</float>';
        $xml .= '<array>1</array><array>2</array><array>3</array>';
        $xml .= '</root>';

        $this->xml = $xml;
        $this->barbarian = file_get_contents(TEMP_DIR . '/barbarian.xml');
    }

    /**
     * Test that is() returns a string of the type name.
     */
    public function testIs() {
        $this->assertEquals('array', Converter::is($this->array));
        $this->assertEquals('object', Converter::is($this->object));
        $this->assertEquals('json', Converter::is($this->json));
        $this->assertEquals('serialized', Converter::is($this->serialized));
        $this->assertEquals('xml', Converter::is($this->xml));

        Converter::macro('isBoolean', function($value) {
            return is_bool($value);
        });

        $this->assertEquals('boolean', Converter::is(true));

        $f = fopen('php://input', 'r');

        $this->assertEquals('resource', Converter::is($f));

        fclose($f);
    }

    /**
     * Test that isArray() only returns true for arrays.
     */
    public function testIsArray() {
        $this->assertTrue(Converter::isArray($this->array));
        $this->assertFalse(Converter::isArray($this->object));
        $this->assertFalse(Converter::isArray($this->json));
        $this->assertFalse(Converter::isArray($this->serialized));
        $this->assertFalse(Converter::isArray($this->xml));
    }

    /**
     * Test that isObject() only returns true for objects.
     */
    public function testIsObject() {
        $this->assertFalse(Converter::isObject($this->array));
        $this->assertTrue(Converter::isObject($this->object));
        $this->assertFalse(Converter::isObject($this->json));
        $this->assertFalse(Converter::isObject($this->serialized));
        $this->assertFalse(Converter::isObject($this->xml));
    }

    /**
     * Test that isJson() only returns true for JSON strings.
     */
    public function testIsJson() {
        $this->assertFalse((bool) Converter::isJson($this->array));
        $this->assertFalse((bool) Converter::isJson($this->object));
        $this->assertTrue((bool) Converter::isJson($this->json));
        $this->assertFalse((bool) Converter::isJson($this->serialized));
        $this->assertFalse((bool) Converter::isJson($this->xml));
    }

    /**
     * Test that isSerialized() only returns true for serialized strings.
     */
    public function testIsSerialized() {
        $this->assertFalse((bool) Converter::isSerialized($this->array));
        $this->assertFalse((bool) Converter::isSerialized($this->object));
        $this->assertFalse((bool) Converter::isSerialized($this->json));
        $this->assertTrue((bool) Converter::isSerialized($this->serialized));
        $this->assertFalse((bool) Converter::isSerialized($this->xml));
    }

    /**
     * Test that isXml() only returns true for XML strings.
     */
    public function testIsXml() {
        $this->assertFalse((bool) Converter::isXml($this->array));
        $this->assertFalse((bool) Converter::isXml($this->object));
        $this->assertFalse((bool) Converter::isXml($this->json));
        $this->assertFalse((bool) Converter::isXml($this->serialized));
        $this->assertTrue((bool) Converter::isXml($this->xml));
    }

    /**
     * Test that toArray() converts any resource type to an array.
     */
    public function testToArray() {
        $this->assertEquals($this->array, Converter::toArray($this->array));
        $this->assertEquals($this->array, Converter::toArray($this->object));
        $this->assertEquals($this->array, Converter::toArray($this->json));
        $this->assertEquals($this->array, Converter::toArray($this->serialized));
        $this->assertEquals($this->array, Converter::toArray($this->xml));

        $test = new TypeContract($this->array);
        $this->assertEquals($this->array, Converter::toArray($test));

        $test = new TypeContract(123);
        $this->assertEquals(array(123), Converter::toArray($test));
    }

    /**
     * Test that toArray() converts all tiers to an array.
     */
    public function testToArrayRecursive() {
        $array = array(
            Converter::toObject(array('key' => 1)),
            array('key' => 2),
            Converter::toObject(array('key' => 3))
        );

        $this->assertEquals($array, Converter::toArray($array));
        $this->assertEquals(array(
            array('key' => 1),
            array('key' => 2),
            array('key' => 3)
        ), Converter::toArray($array, true));
    }

    /**
     * Test that toObject() converts any resource type to an object.
     */
    public function testToObject() {
        $this->assertEquals($this->object, Converter::toObject($this->array));
        $this->assertEquals($this->object, Converter::toObject($this->object));
        $this->assertEquals($this->object, Converter::toObject($this->json));
        $this->assertEquals($this->object, Converter::toObject($this->serialized));
        $this->assertEquals($this->object, Converter::toObject($this->xml));
    }

    /**
     * Test that toObject() converts all tiers to an object.
     */
    public function testToObjectRecursive() {
        $object = new \stdClass();
        $object->a = array('key' => 1);
        $sub = new \stdClass();
        $sub->key = 2;
        $object->b = $sub;
        $object->c = array('key' => 3);

        $this->assertEquals($object, Converter::toObject($object));

        $expected = $object;
        $sub = new \stdClass();
        $sub->key = 1;
        $expected->a = $sub;
        $sub = new \stdClass();
        $sub->key = 3;
        $expected->c = $sub;

        $this->assertEquals($object, Converter::toObject($object, true));
    }

    /**
     * Test that toJson() converts any resource type to a JSON string.
     */
    public function testToJson() {
        $this->assertEquals($this->json, Converter::toJson($this->array));
        $this->assertEquals($this->json, Converter::toJson($this->object));
        $this->assertEquals($this->json, Converter::toJson($this->json));
        $this->assertEquals($this->json, Converter::toJson($this->serialized));
        $this->assertEquals($this->json, Converter::toJson($this->xml));

        $test = new TypeContract($this->array);
        $this->assertEquals($this->json, Converter::toJson($test));

        $test = new TypeContract(array('a' => 1));
        $this->assertEquals('{"a":1}', Converter::toJson($test));
    }

    /**
     * Test that toSerialize() converts any resource type to a serialized string.
     */
    public function testToSerialize() {
        $this->assertEquals($this->serialized, Converter::toSerialize($this->array));
        $this->assertEquals($this->serialized, Converter::toSerialize($this->object));
        $this->assertEquals($this->serialized, Converter::toSerialize($this->json));
        $this->assertEquals($this->serialized, Converter::toSerialize($this->serialized));
        $this->assertEquals($this->serialized, Converter::toSerialize($this->xml));

        $test = new TypeContract($this->array);
        $this->assertEquals('C:26:"Titon\Utility\TypeContract":126:{' . $this->serialized . '}', Converter::toSerialize($test));
    }

    /**
     * Test that toXml() converts any resource type to an XML string.
     */
    public function testToXml() {
        $this->assertEquals($this->xml, Converter::toXml($this->array));
        $this->assertEquals($this->xml, Converter::toXml($this->object));
        $this->assertEquals($this->xml, Converter::toXml($this->json));
        $this->assertEquals($this->xml, Converter::toXml($this->serialized));
        $this->assertEquals($this->xml, Converter::toXml($this->xml));

        $test = new TypeContract($this->array);
        $this->assertEquals($this->xml, Converter::toXml($test));

        $test = new TypeContract(array('a' => 1));
        $this->assertEquals('<?xml version="1.0" encoding="utf-8"?>' . "\n" . '<root><a>1</a></root>', Converter::toXml($test));
    }

    /**
     * Test nested elements and it's related complexity.
     */
    public function testToXmlComplexity() {
        $items = array(
            $this->createXmlItem(1),
            $this->createXmlItem(2),
            $this->createXmlItem(3)
        );

        // Without named indices
        $expected  = '<?xml version="1.0" encoding="utf-8"?>' . "\n" . '<root>';
        $expected .= '<0><id>1</id><title>Item #1</title></0>';
        $expected .= '<1><id>2</id><title>Item #2</title></1>';
        $expected .= '<2><id>3</id><title>Item #3</title></2>';
        $expected .= '</root>';

        // XML nodes cant start with numbers
        try {
            $this->assertXmlStringEqualsXmlString($expected, Converter::toXml($items));
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }

        // With numeric indices
        $numItems = array('item' => array(
            1 => $this->createXmlItem(1)
        ));

        $expected  = '<?xml version="1.0" encoding="utf-8"?><root>';
        $expected .= '<item><id>1</id><title>Item #1</title></item>';
        $expected .= '</root>';

        $this->assertXmlStringEqualsXmlString($expected, Converter::toXml($numItems));

        // With named indices
        $items = array('item' => $items);

        $expected  = '<?xml version="1.0" encoding="utf-8"?><root>';
        $expected .= '<item><id>1</id><title>Item #1</title></item>';
        $expected .= '<item><id>2</id><title>Item #2</title></item>';
        $expected .= '<item><id>3</id><title>Item #3</title></item>';
        $expected .= '</root>';

        $this->assertXmlStringEqualsXmlString($expected, Converter::toXml($items));

        // With deep nested complexity
        $items = array('item' => array(
            $this->createXmlItem(1, true),
            $this->createXmlItem(2, true),
            $this->createXmlItem(3, true)
        ));

        $expected  = '<?xml version="1.0" encoding="utf-8"?><root>';
        $expected .= '<item>
            <id>1</id><title>Item #1</title>
            <foo><id>1</id><title>Item #1</title></foo>
            <foo><id>2</id><title>Item #2</title></foo>
            <foo><id>3</id><title>Item #3</title></foo>
        </item>';
        $expected .= '<item>
            <id>2</id><title>Item #2</title>
            <foo><id>1</id><title>Item #1</title></foo>
            <foo><id>2</id><title>Item #2</title></foo>
            <foo><id>3</id><title>Item #3</title></foo>
        </item>';
        $expected .= '<item>
            <id>3</id><title>Item #3</title>
            <foo><id>1</id><title>Item #1</title></foo>
            <foo><id>2</id><title>Item #2</title></foo>
            <foo><id>3</id><title>Item #3</title></foo>
        </item>';
        $expected .= '</root>';

        $this->assertXmlStringEqualsXmlString($expected, Converter::toXml($items));

        // With deep nested complexity again
        $items = array('item' => array(
            $this->createXmlItem(1, 'a'),
            $this->createXmlItem(2, 'b'),
            $this->createXmlItem(3, 'c')
        ));

        $expected  = '<?xml version="1.0" encoding="utf-8"?><items>';
        $expected .= '<item>
            <id>1</id><title>Item #1</title>
            <foo>
                <a><id>1</id><title>Item #1</title></a>
                <a><id>2</id><title>Item #2</title></a>
                <a><id>3</id><title>Item #3</title></a>
            </foo>
        </item>';
        $expected .= '<item>
            <id>2</id><title>Item #2</title>
            <foo>
                <b><id>1</id><title>Item #1</title></b>
                <b><id>2</id><title>Item #2</title></b>
                <b><id>3</id><title>Item #3</title></b>
            </foo>
        </item>';
        $expected .= '<item>
            <id>3</id><title>Item #3</title>
            <foo>
                <c><id>1</id><title>Item #1</title></c>
                <c><id>2</id><title>Item #2</title></c>
                <c><id>3</id><title>Item #3</title></c>
            </foo>
        </item>';
        $expected .= '</items>';

        $this->assertXmlStringEqualsXmlString($expected, Converter::toXml($items, 'items'));
    }

    /**
     * Test that nested objects within arrays are cast to arrays.
     */
    public function testToXmlArrayOfTypes() {
        $items = array('item' => array(
            Converter::toObject($this->createXmlItem(1)),
            $this->createXmlItem(2),
            Converter::toObject($this->createXmlItem(3))
        ));

        $expected  = '<?xml version="1.0" encoding="utf-8"?><root>';
        $expected .= '<item><id>1</id><title>Item #1</title></item>';
        $expected .= '<item><id>2</id><title>Item #2</title></item>';
        $expected .= '<item><id>3</id><title>Item #3</title></item>';
        $expected .= '</root>';

        $this->assertXmlStringEqualsXmlString($expected, Converter::toXml($items));
    }

    /**
     * Test that type casting works going to/from.
     */
    public function testXmlTypeCasting() {
        $data = array(
            'true' => true,
            'false' => false,
            'null' => null,
            'zero' => 0,
            'empty' => '',
            'float' => 1.50,
            'int' => 666
        );

        $expected  = '<?xml version="1.0" encoding="utf-8"?><root>';
        $expected .= '<true>true</true>';
        $expected .= '<false>false</false>';
        $expected .= '<null></null>';
        $expected .= '<zero>0</zero>';
        $expected .= '<empty></empty>';
        $expected .= '<float>1.5</float>';
        $expected .= '<int>666</int>';
        $expected .= '</root>';

        $this->assertXmlStringEqualsXmlString($expected, Converter::toXml($data));

        $this->assertEquals($data, Converter::xmlToArray(new SimpleXMLElement($expected)));
    }

    /**
     * Test that buildArray() and buildObject() convert all nested tiers.
     */
    public function testBuildArrayObject() {
        $array = array('one' => 1);
        $object = new \stdClass();
        $object->one = 1;

        $this->assertEquals($array, Converter::toArray($object));
        $this->assertEquals($object, Converter::toObject($array));

        $array['one'] = array('two' => 2);
        $level = new \stdClass();
        $level->two = 2;
        $object->one = $level;

        $this->assertEquals($array, Converter::toArray($object));
        $this->assertEquals($object, Converter::toObject($array));

        $array['one']['two'] = array('three' => 3);
        $level = new \stdClass();
        $level->three = 3;
        $object->one->two = $level;

        $this->assertEquals($array, Converter::toArray($object));
        $this->assertEquals($object, Converter::toObject($array));
    }

    /**
     * Test that xmlToArray(XML_NONE) returns the XML without attributes.
     */
    public function testXmlToArrayNone() {
        $expected = array(
            'name' => 'Barbarian',
            'life' => 50,
            'mana' => 100,
            'stamina' => 15,
            'vitality' => 20,
            'dexterity' => '',
            'agility' => '',
            'armors' => array(
                'armor' => array('Helmet', 'Shoulder Plates', 'Breast Plate', 'Greaves', 'Gloves', 'Shield')
            ),
            'weapons' => array(
                'sword' => array('Broadsword', 'Longsword'),
                'axe' => array('Heavy Axe', 'Double-edged Axe'),
                'polearm' => 'Polearm',
                'mace' => 'Mace'
            ),
            'items' => array(
                'potions' => array(
                    'potion' => array('Health Potion', 'Mana Potion')
                ),
                'keys' => array(
                    'chestKey' => 'Chest Key',
                    'bossKey' => 'Boss Key'
                ),
                'food' => array('Fruit', 'Bread', 'Vegetables'),
                'scrap' => 'Scrap'
            )
        );

        $this->assertEquals($expected, Converter::xmlToArray(simplexml_load_string($this->barbarian), Converter::XML_NONE));
    }

    /**
     * Test that xmlToArray(XML_MERGE) returns the XML without attributes.
     */
    public function testXmlToArrayMerge() {
        $expected = array(
            'name' => 'Barbarian',
            'life' => array('value' => 50, 'max' => 150),
            'mana' => array('value' => 100, 'max' => 250),
            'stamina' => 15,
            'vitality' => 20,
            'dexterity' => array('value' => '', 'evade' => '5%', 'block' => '10%'),
            'agility' => array('value' => '', 'turnRate' => '1.25', 'acceleration' => 5),
            'armors' => array(
                'armor' => array(
                    array('value' => 'Helmet', 'defense' => 15),
                    array('value' => 'Shoulder Plates', 'defense' => 25),
                    array('value' => 'Breast Plate', 'defense' => 50),
                    array('value' => 'Greaves', 'defense' => 10),
                    array('value' => 'Gloves', 'defense' => 10),
                    array('value' => 'Shield', 'defense' => 25),
                ),
                'items' => 6
            ),
            'weapons' => array(
                'sword' => array(
                    array('value' => 'Broadsword', 'damage' => 25),
                    array('value' => 'Longsword', 'damage' => 30)
                ),
                'axe' => array(
                    array('value' => 'Heavy Axe', 'damage' => 20),
                    array('value' => 'Double-edged Axe', 'damage' => 25),
                ),
                'polearm' => array('value' => 'Polearm', 'damage' => 50, 'range' => 3, 'speed' => 'slow'),
                'mace' => array('value' => 'Mace', 'damage' => 15, 'speed' => 'fast'),
                'items' => 6
            ),
            'items' => array(
                'potions' => array(
                    'potion' => array('Health Potion', 'Mana Potion')
                ),
                'keys' => array(
                    'chestKey' => 'Chest Key',
                    'bossKey' => 'Boss Key'
                ),
                'food' => array('Fruit', 'Bread', 'Vegetables'),
                'scrap' => array('value' => 'Scrap', 'count' => 25)
            )
        );

        $this->assertEquals($expected, Converter::xmlToArray(simplexml_load_string($this->barbarian), Converter::XML_MERGE));
    }

    /**
     * Test that xmlToArray(XML_GROUP) returns the XML with attributes and value grouped separately.
     */
    public function testXmlToArrayGroup() {
        $expected = array(
            'name' => 'Barbarian',
            'life' => array(
                'value' => 50,
                'attributes' => array('max' => 150)
            ),
            'mana' => array(
                'value' => 100,
                'attributes' => array('max' => 250)
            ),
            'stamina' => 15,
            'vitality' => 20,
            'dexterity' => array(
                'value' => '',
                'attributes' => array('evade' => '5%', 'block' => '10%')
            ),
            'agility' => array(
                'value' => '',
                'attributes' => array('turnRate' => '1.25', 'acceleration' => 5)
            ),
            'armors' => array(
                'value' => array(
                    'armor' => array(
                        array('value' => 'Helmet', 'attributes' => array('defense' => 15)),
                        array('value' => 'Shoulder Plates', 'attributes' => array('defense' => 25)),
                        array('value' => 'Breast Plate', 'attributes' => array('defense' => 50)),
                        array('value' => 'Greaves', 'attributes' => array('defense' => 10)),
                        array('value' => 'Gloves', 'attributes' => array('defense' => 10)),
                        array('value' => 'Shield', 'attributes' => array('defense' => 25)),
                    ),
                ),
                'attributes' => array('items' => 6)
            ),
            'weapons' => array(
                'value' => array(
                    'sword' => array(
                        array('value' => 'Broadsword', 'attributes' => array('damage' => 25)),
                        array('value' => 'Longsword', 'attributes' => array('damage' => 30))
                    ),
                    'axe' => array(
                        array('value' => 'Heavy Axe', 'attributes' => array('damage' => 20)),
                        array('value' => 'Double-edged Axe', 'attributes' => array('damage' => 25)),
                    ),
                    'polearm' => array(
                        'value' => 'Polearm',
                        'attributes' => array('damage' => 50, 'range' => 3, 'speed' => 'slow')
                    ),
                    'mace' => array(
                        'value' => 'Mace',
                        'attributes' => array('damage' => 15, 'speed' => 'fast')
                    ),
                ),
                'attributes' => array('items' => 6)
            ),
            'items' => array(
                'potions' => array(
                    'potion' => array('Health Potion', 'Mana Potion')
                ),
                'keys' => array(
                    'chestKey' => 'Chest Key',
                    'bossKey' => 'Boss Key'
                ),
                'food' => array('Fruit', 'Bread', 'Vegetables'),
                'scrap' => array(
                    'value' => 'Scrap',
                    'attributes' => array('count' => 25)
                )
            )
        );

        $this->assertEquals($expected, Converter::xmlToArray(simplexml_load_string($this->barbarian), Converter::XML_GROUP));
    }

    /**
     * Test that xmlToArray(XML_ATTRIBS) returns the XML with only attributes.
     */
    public function testXmlToArrayAttribs() {
        $expected = array(
            'name' => 'Barbarian',
            'life' => array('max' => 150),
            'mana' => array('max' => 250),
            'stamina' => 15,
            'vitality' => 20,
            'dexterity' => array('evade' => '5%', 'block' => '10%'),
            'agility' => array('turnRate' => '1.25', 'acceleration' => 5),
            'armors' => array(
                'items' => 6
            ),
            'weapons' => array(
                'items' => 6
            ),
            'items' => array(
                'potions' => array(
                    'potion' => array('Health Potion', 'Mana Potion')
                ),
                'keys' => array(
                    'chestKey' => 'Chest Key',
                    'bossKey' => 'Boss Key'
                ),
                'food' => array('Fruit', 'Bread', 'Vegetables'),
                'scrap' => array('count' => 25)
            )
        );

        $this->assertEquals($expected, Converter::xmlToArray(simplexml_load_string($this->barbarian), Converter::XML_ATTRIBS));
    }

    /**
     * Create an array to use for an XML element.
     *
     * @param int $id
     * @param boolean|string $complex
     * @return array
     */
    protected function createXmlItem($id, $complex = false) {
        $item = array('id' => $id, 'title' => 'Item #' . $id);

        if ($complex) {
            $item['foo'] = array(
                $this->createXmlItem(1),
                $this->createXmlItem(2),
                $this->createXmlItem(3)
            );

            if ($complex !== true) {
                $item['foo'] = array($complex => $item['foo']);
            }
        }

        return $item;
    }

}

class TypeContract implements Arrayable, Jsonable, Xmlable, \Serializable {

    protected $data = array();

    public function __construct($data) {
        $this->data = $data;
    }

    public function toArray() {
        return (array) $this->data;
    }

    public function toJson($options = 0) {
        return json_encode($this->data, $options);
    }

    public function toXml($root = 'root') {
        return Converter::toXml($this->toArray(), $root);
    }

    public function serialize() {
        return serialize($this->data);
    }

    public function unserialize($data) {
        $this->data = unserialize($data);
    }

}