<?php

namespace spartaksun\addresses\form;


use spartaksun\addresses\components\Db;

/**
 * Abstract form
 * @package spartaksun\addresses\form
 */
abstract class Form
{
    /**
     * @var array of all received attributes
     */
    public $attributes = array();
    /**
     * @var Db
     */
    public $db;
    /**
     * @var array attributes to save
     */
    protected $attributesToSave = array();
    /**
     * @var array of form errors
     */
    private $_errors = array();


    /**
     * Validators
     * Only attributes described here will be saved
     * @return array
     */
    abstract protected function validators();


    public function __construct()
    {
        $this->db = Db::getInstance();
        mb_internal_encoding('utf-8');
    }

    /**
     * Populate attributes and run filters and validators
     * @param array $attributes
     * @return bool
     */
    public function load(array $attributes)
    {
        $validators = $this->validators();
        $this->attributes = array_merge($this->attributes, $attributes);
        foreach ($this->attributes as $attribute => $value) {
            if (array_key_exists($attribute, $validators)) {
                call_user_func_array($validators[$attribute], array($attribute));
                $this->attributesToSave[$attribute] = $value;
            }
        }

        return empty($this->_errors);
    }

    /**
     * Turn attribute into lower case
     * @param $attribute
     */
    public function filterLowerCase($attribute)
    {
        $this->setAttribute($attribute, mb_strtolower(
            $this->getAttribute($attribute)
        ));
    }

    /**
     * Trim filter
     * @param $attribute
     */
    public function filterTrim($attribute)
    {
        $this->setAttribute($attribute, trim(
            $this->getAttribute($attribute)
        ));
    }

    /**
     * Email validator
     * @param $attribute
     * @see http://www.regular-expressions.info/email.html
     */
    public function validateEmail($attribute)
    {
        $value = $this->getAttribute($attribute);
        $pattern='/^[а-яёa-z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zа-яё0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
        if (!preg_match($pattern, $value)) {
            $this->addError($attribute, 'Invalid email.');
        }
    }

    /**
     * Check if attribute is unique in database
     * @param $attribute
     */
    public function validateUnique($attribute, $tableName)
    {
        $object = $this->db->select($tableName, array(
            $attribute => $this->getAttribute($attribute),
        ));
        if (!empty($object)) {
            $this->addError($attribute, "Attribute {$attribute} is not unique.");
        }
    }

    /**
     * String length validator
     * @param $attribute
     * @param array $length
     */
    public function validateLength($attribute, array $length)
    {
        $strLength = mb_strlen($this->getAttribute($attribute));
        if (!empty($length['min'])) {
            if ($strLength < $length['min']) {
                $this->addError($attribute, "Length of {$attribute} is too short. Minimum {$length['min']} characters.");
            }
        }

        if (isset($length['max'])) {
            if ($strLength > $length['max']) {
                $this->addError($attribute, "Length of {$attribute} is too long. Maximum {$length['max']} characters.");
            }
        }
    }

    /**
     * "Not empty" validator
     * @param $attribute
     */
    public function validateNotEmpty($attribute)
    {
        $value = $this->getAttribute($attribute);
        if(empty($value)) {
            $this->addError($attribute, "Attribute {$attribute} can not be empty.");
        }
    }

    /**
     * Value of attribute
     * @param $attribute
     * @return string
     */
    public function getAttribute($attribute)
    {
        return isset($this->attributes[$attribute])
            ? $this->attributes[$attribute]
            : '';
    }

    /**
     * Set value of attribute
     * @param $name
     * @param $value
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Class for attribute input
     * @param $attribute
     * @return string
     */
    public function attributeCssClass($attribute)
    {
        $error = $this->getError($attribute);
        return !empty($error)
            ? 'class="error"'
            : '';
    }

    /**
     * Add error string to attribute
     * @param $attribute
     * @param $text
     */
    public function addError($attribute, $text)
    {
        $this->_errors[$attribute][] = $text;
    }

    /**
     * All form errors
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Errors of one attribute
     * @param $attribute
     * @return bool
     */
    public function getError($attribute)
    {
        return isset($this->_errors[$attribute])
            ? $this->_errors[$attribute]
            : false;
    }
}