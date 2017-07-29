<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 29/07/17
 * Time: 08:00
 */

namespace Pkeys;


/**
 * Class Key
 * @package Pkeys
 */
use Illuminate\Support\Str;
use Pkeys\Exceptions\MissingParamsException;
use Pkeys\Exceptions\ValidationException;
use Pkeys\Interfaces\ValidatorInterface;
use Pkeys\Validation\ValidationRules;

/**
 * Class Key
 * @package Pkeys
 */
class Key
{
    /**
     * Default set of key delimiters
     */
    const DELIMITERS = [':','.','-'];

    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
    protected $definedParams = [];

    /**
     * @var array
     */
    protected $requiredParams = [];

    /**
     * @var array
     */
    protected $optionalParams = [];

    /**
     * @var array
     */
    protected $validateParams = [];

    /**
     * @var array
     */
    protected $validator;

    /**
     * @var array
     */
    protected $delimiters = [];

    /**
     * @var string
     */
    protected $key;

    /**
     * Key constructor.
     */
    public function __construct($pattern,$params = [])
    {
        $this->setDelimiters(self::DELIMITERS);
        $this->setPattern($pattern);
        $this->setParams($params);
        $this->validator = new ValidationRules();
    }

    public function build()
    {
        $this->validateParams();
        $this->parsePattern();
        return $this;
    }

    /**
     * @throws MissingParamsException
     * @return $this
     */
    public function validateParams()
    {
        $this->parseDefinedParams();

        $diff = array_diff($this->getRequiredParams(),array_keys($this->getParams()));
        if(!empty($diff)){
            Throw new MissingParamsException('Missing the following required params: '.implode(',',$diff));
        }

        foreach($this->getValidateParams() as $paramName=>$rule){
            $args = explode(':',$rule);
            $ruleName = array_shift($args);
            $args = array_prepend($args,$this->getParam($paramName));

            dd($this->validator->$ruleName($args));
            if(method_exists($this->getValidator(),$ruleName)) {
                if (!call_user_func_array([$this->validator, $ruleName], $args)) {
                    Throw new ValidationException('Key parameter "' . $args[0] . '" failed "' . $ruleName . '" validation.');
                }
            }elseif(property_exists($this->getValidator(),$ruleName)){
                dd('d');
            }else{
                Throw new ValidationException('Validation rule "' . $ruleName . '" does not exist.');
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function parseDefinedParams()
    {
        $matches = [];
        preg_match_all('/{(.*?)}/', $this->getPattern(), $matches);
        $this->definedParams = isset($matches[1])?$matches[1]:[];
        $pattern = $this->getPattern();

        foreach($this->getDefinedParams() as $definedParam){

            /*
             * Does it require validation
             */
            $parts = explode('|',$definedParam);
            if(str_contains($definedParam,'|')){
                $rule = rtrim($parts[1],'?');
                $this->validateParams[$parts[0]]=$rule;
            }

            /*
             * Optional or required param
             */
            if(Str::endsWith($definedParam, '?')){
                $this->optionalParams[]=rtrim($parts[0],'?');
            }else{
                $this->requiredParams[]=$parts[0];
            }

            $pattern = str_replace($definedParam,$parts[0],$pattern);
        }

        $this->setPattern($pattern);

        return $this;
    }

    /**
     *
     */
    public function parsePattern()
    {

    }


    /**
     * @return mixed
     */
    public function getDelimiters()
    {
        return $this->delimiters;
    }

    /**
     * @param mixed $delimiters
     */
    public function setDelimiters(array $delimiters)
    {
        $this->delimiters = $delimiters;
        return $this;
    }

    /**
     * @param $delimiter
     * @return $this
     */
    public function addDelimiter($delimiter)
    {
        $this->delimiters[]=$delimiter;
        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return array
     */
    public function getParam($param)
    {
        return $this->params[$param];
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function getDefinedParams()
    {
        return $this->definedParams;
    }

    /**
     * @param array $definedParams
     * @return $this
     */
    public function setDefinedParams($definedParams)
    {
        $this->definedParams = $definedParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    /**
     * @param array $requiredParams
     * @return $this
     */
    public function setRequiredParams($requiredParams)
    {
        $this->requiredParams = $requiredParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptionalParams()
    {
        return $this->optionalParams;
    }

    /**
     * @param array $optionalParams
     * @return $this
     */
    public function setOptionalParams($optionalParams)
    {
        $this->optionalParams = $optionalParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getValidateParams()
    {
        return $this->validateParams;
    }

    /**
     * @param array $validateParams
     */
    public function setValidateParams($validateParams)
    {
        $this->validateParams = $validateParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param array $validator
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * @param $ruleName
     * @param callable $rule
     * @return $this
     */
    public function addCustomValidationRule($ruleName, callable $rule)
    {
        $this->validator->addRule($ruleName,$rule);
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getKey();
    }
}