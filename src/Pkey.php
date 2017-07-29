<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 29/07/17
 * Time: 07:49
 */

namespace Pkeys;

use Illuminate\Support\Arr;
use Pkeys\Exceptions\NoKeyException;
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
     * @var ValidatorInterface
     */
    protected $customValidator;

    /**
     * Pkey constructor.
     * @param $schemaPath
     * @param ValidatorInterface|null $customValidator
     */
    public function __construct($schema,ValidatorInterface $customValidator =null )
    {
        if(is_array($schema)){
            $this->setSchema($schema);
        }else{
            $this->setSchema(include $schema);
        }
        $this->setCustomValidator($customValidator);
    }

    /**
     * @param string $index
     * @param array $params
     * @return Key
     * @throws PkeyException
     */
    public function make($index,$params = [])
    {
        $schema = $this->getSchema();
        if($pattern = Arr::get($schema['schema'],$index)){

            $key =  new Key($pattern,$params);

            if(isset($schema['delimiters'])){
                $key->setDelimiters($schema['delimiters']);
            }

            if($validator = $this->getCustomValidator()){
                $key->setValidator($validator);
            }

            return $key->build();
        }

        Throw new NoKeyException('Cannot find a key pattern with this index: '.$index);
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
     * @param ValidatorInterface $customValidator
     * @return $this
     */
    public function setCustomValidator(ValidatorInterface $customValidator = null)
    {
        $this->customValidator = $customValidator;
        return $this;
    }

}