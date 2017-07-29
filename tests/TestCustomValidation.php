<?php

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 29/07/17
 * Time: 12:14
 */
class TestCustomValidation extends TestCase
{

    public function test_loading_custom_validation_rule()
    {
        $pattern = 'this:is:{a|ruledoesntexist}:pattern';
        $key = new \Pkeys\Key($pattern,['a'=>'whatevers']);
        $key->addCustomValidationRule('ruledoesntexit',function($args){return $args[0]=='whatevers';});

        $this->assertTrue(property_exists($key->getValidator(),'ruledoesntexit'));
        $key->build();
    }
}