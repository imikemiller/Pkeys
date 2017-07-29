<?php

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 29/07/17
 * Time: 11:35
 */
class TestValidation extends TestCase
{
    /**
     * @var
     */
    public $validator;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->validator = new \Pkeys\Validation\ValidationRules();
    }

    public function test_alpha_fails()
    {
        $this->assertTrue(!$this->validator->alpha('notalpha1'));
    }

    public function test_alpha_passes()
    {
        $this->assertTrue($this->validator->alpha('isalpha'));
    }

    public function test_alphaNum_fails()
    {
        $this->assertTrue(!$this->validator->alphaNum('notalpha@'));
    }

    public function test_alphaNum_passes()
    {
        $this->assertTrue($this->validator->alphaNum('isalpha1'));
    }

    public function test_numeric_fails()
    {
        $this->assertTrue(!$this->validator->numeric('notalpha@'));
    }

    public function test_numeric_passes()
    {
        $this->assertTrue($this->validator->numeric('22'));
        $this->assertTrue($this->validator->numeric(33));
    }

    public function test_date_fails()
    {
        $this->assertTrue(!$this->validator->date('notdate'));
    }

    public function test_date_passes()
    {
        $this->assertTrue($this->validator->date('2017-12-12'));
        $this->assertTrue($this->validator->date('2017-12-12 12:12:12'));
        $this->assertTrue($this->validator->date('12/12/2012'));
    }

    public function test_email_fails()
    {
        $this->assertTrue(!$this->validator->email('notemail@'));
    }

    public function test_email_passes()
    {
        $this->assertTrue($this->validator->email('email@me.com'));
        $this->assertTrue($this->validator->email('mike@mikemiller.uk'));
    }

    public function test_in_fails()
    {
        $this->assertTrue(!$this->validator->in('notin','this,list,of,things'));
    }

    public function test_in_passes()
    {
        $this->assertTrue($this->validator->in('in','is,in,this,list'));
    }

    public function test_notIn_fails()
    {
        $this->assertTrue(!$this->validator->notIn('in','in,this,list,of,things'));
    }

    public function test_notIn_passes()
    {
        $this->assertTrue($this->validator->notIn('notIn','is,not,in,this,list'));
    }

    public function test_json_fails()
    {
        $this->assertTrue(!$this->validator->json('notalpha@'));
    }

    public function test_json_passes()
    {
        $this->assertTrue($this->validator->json(json_encode(['is'=>'json'])));
    }
}