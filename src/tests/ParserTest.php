<?php

use App\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    private Parser $parser;

    // If required is false, default must be set
    // boolean having defalt of true makes no sense, you can
    // Requesting multi word string with different quotes (' " or " ') doesn't work
    // either rework the regex or walk the string and parse it manually
    private array $schema = [
        'a' => ['type' => 'bool', 'default' => false],
        'b' => ['type' => 'bool', 'default' => false],
        'c' => ['type' => 'int', 'required' => false, 'default' => 123],
        'd' => ['type' => 'int', 'required' => true, 'default' => 0],
        'e' => ['type' => 'string', 'required' => false, 'default' => 'Hello World'],
        'f' => ['type' => 'string', 'required' => true,],
    ];

    protected function setUp(): void
    {
        $this->parser = new Parser($this->schema);
    }

    public function testEmpty()
    {
        $this->assertEmpty($this->parser->parse("")->getValues());
    }

    public function testWhiteSpaceString()
    {
        $this->assertEmpty($this->parser->parse("  ")->getValues());
    }

    public function testSpecialCharacters()
    {
        $this->assertEmpty($this->parser->parse("  \n \r \t ; . ' $")->getValues());
    }

    public function testDashWithoutArgKey()
    {
        $this->assertEmpty($this->parser->parse("--")->getValues());
    }

    public function testInvalidKeys()
    {
        $this->assertEmpty($this->parser->parse("-z")->getValues());
    }

    public function testProvidedValidBoolReturnsTrue()
    {
        $result = $this->parser->parse("-a");

        $this->assertTrue($result->get('a'));
    }

    public function testNotProvidedValidBoolDefaultsToFalse()
    {
        $result = $this->parser->parse("");

        $this->assertFalse($result->get('a'));
    }

    public function testProvidedBoolWithFalseDefaultIsTrue()
    {
        $result = $this->parser->parse('-a');
        
        $this->assertTrue($result->get('a'));
    }

    public function testNonRequiredIntWithDefaultValue()
    {
        $result = $this->parser->parse('');
        
        $this->assertIsInt($result->get('c'));
        $this->assertSame(123, $result->get('c'));
    }

    public function testIntKeyWithNoValueProvided()
    {
        $this->expectExceptionObject(new Exception("INT_KEY_WITH_NO_VALUE"));
        $this->parser->parse('-c');
    }

    public function testIntKeyWithValidIntProvided()
    {
        $result = $this->parser->parse('-c 456');
        $this->assertSame(456, $result->get('c'));
    }

    public function testIntKeyWithValidFloatProvided()
    {
        $result = $this->parser->parse('-c 456.4');
        $this->assertSame(456, $result->get('c'));
    }

    public function testIntKeyWithNonNumericValue()
    {
        $this->expectExceptionObject(new Exception("NON_NUMERIC_VALUE_FOR_INT_KEY"));
        $this->parser->parse('-c abc');
    }

    public function testIntKeyWithNegativeValue()
    {
        $result = $this->parser->parse('-c -123');
        $this->assertSame(-123, $result->get('c'));
    }

    // Test for required int ( required is not implemented currently )

    public function testNotProvidedStringKeyReturnsDefaultValue()
    {
        $result = $this->parser->parse('');
        $this->assertSame('Hello World', $result->get('e'));
    }

    public function testProvidedStringKeyWithNoValueThrows()
    {
        $this->expectExceptionObject(new Exception("STRING_KEY_WITH_NO_VALUE"));
        $this->parser->parse('-e  ');
    }

    public function testProvidedStringKeyWithValue()
    {
        $result = $this->parser->parse('-e 123abc');
        $this->assertSame('123abc', $result->get('e'));
    }

    public function testProvidedStringKeyWithSpecialCharacters()
    {
        $result = $this->parser->parse("-e \n\t123abc");
        $this->assertSame('123abc', $result->get('e'));
    }

    public function testProvidedStringKeyWithStringValueThatHasMultipleWords()
    {
        $result = $this->parser->parse('-f "Hello World"');
        $this->assertSame('Hello World', $result->get('f'));
    }

    // Test for required string

    public function testMultipleFlagCombinations()
    {
        $result = $this->parser->parse('-a -d 15 -d 13 -e 123 -f \'Hello World\'');

        $this->assertTrue($result->get('a'));
        $this->assertFalse($result->get('b'));
        $this->assertSame(123, $result->get('c'));
        $this->assertSame(13, $result->get('d'));
        $this->assertSame('123', $result->get('e'));
        $this->assertSame('Hello World', $result->get('f'));
    }
}