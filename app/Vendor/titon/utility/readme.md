# Utility v0.13.4 [![Build Status](https://travis-ci.org/titon/utility.png)](https://travis-ci.org/titon/utility) #

The Titon utility package provides lightweight static classes for common tasks.
All utilities extend a powerful macro system which permits the addition of new static methods at runtime.

```php
use Titon\Utility\Inflector;

Inflector::macro('upperCase', function($value) {
    return strtoupper($value);
});

Inflector::upperCase('foo'); // FOO
```

Amongst the robust macro system, we can easily execute other functionality, for example.
The `Converter` converts one type of data to another.

```php
Titon\Utility\Converter::toArray($xml); // Supports arrays, objects, JSON, XML, serialized
```

`Crypt` encrypts and decrypts data through the mcrypt extension.

```php
Titon\Utility\Crypt::blowfish('foobar', $salt);
```

`Format` formats strings into common patterns.

```php
Titon\Utility\Format::phone('1234567890', '(###) ###-####'); // (123) 456-7890
```

`Hash` mutates or accesses data in arrays.

```php
Titon\Utility\Hash::set($array, 'foo.bar', 'baz');
```

`Inflector` rewrites strings to specific forms.

```php
Titon\Utility\Inflector::camelCase('foo bar'); // FooBar
```

`Number` applies calculations or evaluations on numbers.

```php
Titon\Utility\Number::bytesTo(1024); // 1KB
```

`Path` resolves file paths, namespaces, and class names.

```php
Titon\Utility\Path::className('Vendor\Foo\Bar'); // Bar
```

`Sanitize` cleans and filters data.

```php
Titon\Utility\Sanitize::html($data);
```

`String` modifies and analyzes strings.

```php
Titon\Utility\String::truncate($string);
```

`Time` evaluates and compares dates and times.

```php
Titon\Utility\Time::isTomorrow($time);
```

`Validate` validates data with defined rules and patterns.
Can be used in combination with the `Validator`, which provides an object oriented approach to data validation.

```php
Titon\Utility\Validate::ext($path, ['gif', 'png', 'jpg']);
```

Most of the functionality found in the static classes can also be found in global functions.
The list of all functions can be found in the bootstrap file within the source folder.

### Features ###

* `Converter` - Convert one type to another
* `Crypt` - Data encryption and decryption
* `Format` - Data formatting
* `Hash` - Object and array manipulation
* `Inflector` - String and grammar formatting
* `Number` - Number manipulation
* `Path` - File system and namespace path formatting
* `Sanitize` - Data cleaning and escaping
* `String` - String manipulation
* `Time` - Date and time manipulation
* `Validate` - Data validation and filtering
* `Validator` - Schema Rule Validation

### Requirements ###

* PHP 5.3.0
    * Multibyte
    * SimpleXML (for Converter)
    * Mcrypt (for Crypt)
    * Fileinfo (for Validate)