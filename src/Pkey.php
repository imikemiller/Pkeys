<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 29/07/17
 * Time: 07:49
 */

namespace Pkeys;

use Illuminate\Support\Arr;
use Pkeys\Exceptions\PkeyException;
use Pkeys\Interfaces\ValidatorInterface;

/**
 * Class Pkey
 * @package Pkeys
 */
class Pkey
{
    /**
     * @var array
     */
    protected $schema =[];

    /**
     * @var
     */
    protected $customValidator;

    /**
     * Pkey constructor.
     * @param $schemaPath
     */
    public function __construct($schemaPath,ValidatorInterface $customValidator =null )
    {
        $this->setSchema(include $schemaPath);
        $this->setCustomValidator($customValidator);
    }

    /**
     * @param $index
     * @return Key
     * @throws PkeyException
     */
    public function make($index)
    {
        $schema = $this->getSchema();
        if($pattern = Arr::get($schema['schema'],$index)){
            $key =  new Key($pattern);

            if(isset($schema['delimiters'])){
                $key->setDelimiters($schema['delimiters']);
            }

            if($validator = $this->getCustomValidator()){
                $key->setValidator($validator);
            }

            return $key->build();
        }

        Throw new PkeyException('Cannot find a key pattern with this index: '.$index);
    }

    /**
     * @return array
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param array $schema
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomValidator()
    {
        return $this->customValidator;
    }

    /**
     * @param mixed $customValidator
     */
    public function setCustomValidator(ValidatorInterface $customValidator)
    {
        $this->customValidator = $customValidator;
        return $this;
    }

}