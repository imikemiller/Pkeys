<?php

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 29/07/17
 * Time: 09:22
 */
class TestKey extends TestCase
{
    /**
     *
     */
    public function test_loading_pattern()
    {
        $pattern = 'this:is:some:pattern';
        $key = (new \Pkeys\Key($pattern))->build();
        $this->assertEquals($pattern,$key->getPattern());
    }

    /**
     * @expectedException \Pkeys\Exceptions\MissingParamsException
     */
    public function test_parsing_defined_params()
    {
        $pattern = 'this:is:{a}:pattern:{with}:several:{params}';
        $key = (new \Pkeys\Key($pattern))->build();
        $this->assertEquals(['a','with','params'],$key->getDefinedParams());
    }

    /**
     * @expectedException \Pkeys\Exceptions\MissingParamsException
     */
    public function test_parsing_required_and_optional_params()
    {
        $pattern = 'this:is:{a}:pattern:{with?}:several:{params|numeric}:in:{it?}';
        $key = (new \Pkeys\Key($pattern))->build();
        $this->assertEquals(['a','with?','params:numeric','it?'],$key->getDefinedParams());
        $this->assertEquals(['with','it'],$key->getOptionalParams());
        $this->assertEquals(['a','params'],$key->getRequiredParams());
        $this->assertEquals(['params'=>'numeric'],$key->getValidations());
    }

    /**
     *
     */
    public function test_with_required_params()
    {
        $pattern = 'this:is:{a}:pattern:{with?}:several:{params|numeric}:in:{it?}';
        $key = (new \Pkeys\Key($pattern,['a'=>11,'params'=>22]))->build();
        $this->assertEquals(['a'=>11,'params'=>22],$key->getParams());

        $key = new \Pkeys\Key($pattern,['a'=>11,'params'=>22,'extra'=>'param']);
        $this->assertEquals(['a'=>11,'params'=>22,'extra'=>'param'],$key->getParams());
    }

    /**
     *
     */
    public function test_applied_validation_rules()
    {
        $pattern = 'this:is:{a|alpha}:pattern:{with|alphaNum}:several:{params|date}:in:{it|email}';
        $key = (new \Pkeys\Key($pattern,['a'=>'alpha','params'=>'2017-12-12','with'=>'alpha1','it'=>'test@pkeys.com']))->build();
        $this->assertInstanceOf(\Pkeys\Key::class,$key);

        $pattern = 'this:is:{a|json}:pattern:{with|in:yes,no,why?}:several:{params|numeric}:in:{it|notIn:do,wah,day?}';
        $key = (new \Pkeys\Key($pattern,['a'=>json_encode(['some'=>'thing']),'params'=>22,'with'=>'yes','it'=>'roger']))->build();
        $this->assertInstanceOf(\Pkeys\Key::class,$key);
    }

    /**
     * @expectedException \Pkeys\Exceptions\ValidationException
     */
    public function test_validation_rule_does_not_exist()
    {
        $pattern = 'this:is:{a|ruledoesntexist}:pattern';
        $key = (new \Pkeys\Key($pattern,['a'=>'whatevers']))->build();
        $this->assertInstanceOf(\Pkeys\Key::class,$key);
    }

    public function test_key_generation()
    {
        $pattern = 'this:is:{a}:pattern:{with}:several:{params|numeric}:in:{it}';
        $key = (new \Pkeys\Key($pattern,['a'=>11,'with'=>22,'params'=>'33','it'=>44]))->build();
        $this->assertEquals('this:is:11:pattern:22:several:33:in:44',$key->getKey());

        $pattern = 'this:is:{a}:pattern:{with}:several:{params|numeric}:in:{it?}';
        $key = (new \Pkeys\Key($pattern,['a'=>11,'with'=>22,'params'=>'33']))->build();

        $this->assertEquals('this:is:11:pattern:22:several:33:in',$key->getKey());

        $pattern = '{this?}:is:{a}:pattern:{with}:several:{params|numeric}:in:{it?}';
        $key = (new \Pkeys\Key($pattern,['a'=>11,'with'=>22,'params'=>'33']))->build();

        $this->assertEquals('is:11:pattern:22:several:33:in',$key->getKey());

        $pattern = '{this?}:is:{a}:pattern:{with}:{several?}:{params|numeric}:in:{it?}';
        $key = (new \Pkeys\Key($pattern,['a'=>11,'with'=>22,'params'=>'33']))->build();

        $this->assertEquals('is:11:pattern:22:33:in',$key->getKey());

        $pattern = '{this?}:is:{a}:pattern:{with}:{several?}~{params|numeric}:in:{it?}';
        $key = new \Pkeys\Key($pattern,['a'=>11,'with'=>22,'params'=>'33']);
        $key->setDelimiters([':','~']);
        $key->build();

        $this->assertEquals('is:11:pattern:22:33:in',$key->getKey());


    }
}

























