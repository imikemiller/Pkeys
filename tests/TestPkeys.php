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
        $key = $pkey->make('redis.user.messages',['id'=>22]);
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

    /**
     *
     */
    public function test_custom_validation_success()
    {
        $customValidator = new CustomValidator();
        $pkey = new Pkey('tests/schema.php',$customValidator);
        $key = $pkey->make('test.custom.success',['id'=>'anything']);
        $this->assertInstanceOf(\Pkeys\Key::class,$key);
    }

    /**
     * @expectedException \Pkeys\Exceptions\ValidationException
     */
    public function test_custom_validation_fail()
    {
        $customValidator = new CustomValidator();
        $pkey = new Pkey('tests/schema.php',$customValidator);
        $key = $pkey->make('test.custom.fail',['id'=>'anything']);
        $this->assertInstanceOf(\Pkeys\Key::class,$key);
    }
}

/**
 * Class CustomValidator
 */
class CustomValidator implements \Pkeys\Interfaces\ValidatorInterface{
    /**
     * @param $value
     * @return bool
     */
    public function customSuccess($value)
    {
        return true;
    }

    /**
     * @param $value
     * @return bool
     */
    public function customFail($value)
    {
        return false;
    }
}