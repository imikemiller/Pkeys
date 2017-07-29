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
     * Pkey constructor.
     * @param $schemaPath
     */
    public function __construct($schemaPath)
    {
        $this->setSchema(include $schemaPath);
    }

    public function make($index)
    {
        if($pattern = Arr::get($this->getSchema(),$index)){
            return new Key($pattern);
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

}