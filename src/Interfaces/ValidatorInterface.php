<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 29/07/17
 * Time: 12:25
 */

namespace Pkeys\Interfaces;


interface ValidatorInterface
{
    public function addRule($ruleName,callable $rule);

    public function __call($ruleName, $arguments);
}