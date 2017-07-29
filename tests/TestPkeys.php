<?php
use Pkeys\Pkey;

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 29/07/17
 * Time: 08:20
 */
class TestPkeys extends TestCase
{
    /**
     * Test loading schema array from file
     */
    public function test_loading_schema_array()
    {
        $schema = include 'tests/schema.php';
        $pkey = new Pkey('tests/schema.php');
        $this->assertEquals($schema,$pkey->getSchema());

        return $pkey;
    }

    /**
     * Test making a Pkeys/Key from index
     *
     * @depends test_loading_schema_array
     */
    public function test_make_key_object(Pkey $pkey)
    {
        $key = $pkey->make('redis.user.messages');
        $this->assertInstanceOf(\Pkeys\Key::class,$key);
    }

    /**
     * Test making a Pkeys/Key from index
     *
     * @depends test_loading_schema_array
     * @expectedException \Pkeys\Exceptions\PkeyException
     */
    public function test_exception_on_unknown_index(Pkey $pkey)
    {
        $pkey->make('this.does.not.exist');
    }
}