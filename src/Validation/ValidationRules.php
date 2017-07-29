<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 29/07/17
 * Time: 10:42
 */

namespace Pkeys\Validation;


use DateTimeInterface;
use Pkeys\Interfaces\ValidatorInterface;

/**
 * Class ValidationRules
 * @package Pkeys\Validation
 */
class ValidationRules implements ValidatorInterface
{

    /**
     * @param $ruleName
     * @param callable $rule
     */
    public function addRule($ruleName, callable $rule)
    {
        $this->{$ruleName}=$rule;
    }

    /**
     * @param $ruleName
     * @param $arguments
     * @return mixed
     */
    public function __call($ruleName, $arguments)
    {
        return call_user_func_array($this->{$ruleName}, $arguments);
    }

    /**
     * Is alpha chars only.
     *
     * @param  mixed   $value
     * @return bool
     */
    public function alpha($value)
    {
        return is_string($value) && preg_match('/^[\pL\pM]+$/u', $value);
    }

    /**
     * Is alpha-numeric chars only.
     *
     * @param  mixed   $value
     * @return bool
     */
    public function alphaNum($value)
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }
        return preg_match('/^[\pL\pM\pN]+$/u', $value) > 0;
    }

    /**
     * Is numeric.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function numeric($value)
    {
        return is_numeric($value);
    }

    /**
     * Is a valid date.
     *
     * @param  mixed   $value
     * @return bool
     */
    public function date($value)
    {
        if ($value instanceof DateTimeInterface) {
            return true;
        }
        if ((! is_string($value) && ! is_numeric($value)) || strtotime($value) === false) {
            return false;
        }
        $date = date_parse($value);
        return checkdate($date['month'], $date['day'], $date['year']);
    }

    /**
     * Is a valid e-mail address.
     *
     * @param  mixed   $value
     * @return bool
     */
    public function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Is within a list of values.
     *
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function in($value, $list)
    {
        $list = explode(',',$list);
        return in_array((string) $value, $list);
    }

    /**
     * Validate an attribute is not contained within a list of values.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function notIn($value, $list)
    {
        return ! $this->in($value, $list);
    }

    /**
     * Is a valid JSON string.
     *
     * @param  mixed   $value
     * @return bool
     */
    public function json($value)
    {
        if (! is_scalar($value) && ! method_exists($value, '__toString')) {
            return false;
        }
        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE;
    }
}