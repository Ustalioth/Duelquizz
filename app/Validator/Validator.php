<?php

namespace App\Validator;

use App\Core\Config\Config;

class Validator
{
    protected array $errors = [];

    public function validate(object $entity): bool
    {
        $this->errors = [];

        $rules = require Config::config('validation_rules_path');

        $className = get_class($entity);
        if (isset($rules[$className])) {
            $rules = $rules[$className];

            foreach ($rules as $property => $propertyRules) {
                foreach ($propertyRules as $rule) {
                    $value = $this->getValue($property, $entity);

                    if ($this->checkValue($value, $rule) === false) {
                        $this->errors[$property][] = $rule['message'];
                    }
                }
            }
        }

        return $this->isValid();
    }

    public function isValid(): bool
    {
        return count($this->errors) === 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    protected function checkValue($value, array $rule)
    {
        switch ($rule['rule']) {
            case 'not_blank':
                return !empty($value);
                break;
            case 'no_space':
                return strpos($value, ' ') === false;
                break;
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL);
                break;
            case 'length':
                if (isset($rule['min']) && strlen($value) < $rule['min']) {
                    return false;
                }

                if (isset($rule['max']) && strlen($value) > $rule['max']) {
                    return false;
                }
                return true;
                break;

            default:
                throw new \Exception('Unknown rule ' . $rule['rule']);
                break;
        }
        echo '<pre>';print_r($rule);die;
    }

    protected function getValue(string $property, object $entity)
    {
        $reflectionClass = new \ReflectionClass($entity);
        if ($reflectionClass->hasProperty($property)) {
            $reflectionProperty = $reflectionClass->getProperty($property);
            $reflectionProperty->setAccessible(true);

            return $reflectionProperty->getValue($entity);
        }

        throw new \Exception('Property does not exists');
    }
}
