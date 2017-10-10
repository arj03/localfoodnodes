<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;
use Validator;

class BaseModel extends Model
{
    /**
     * Validate object.
     *
     * @param array $data
     * @return array|bool validation result
     */
    public function validate($data, $rules = [], $messages = [])
    {
        if (empty($rules)) {
            $rules = $this->validationRules;
        }

        if (isset($this->validationMessages)) {
            $messages = array_merge($this->validationMessages, $messages);
        }

        $validation = Validator::make($data, $rules, $messages);
        $errors = new MessageBag();

        if ($validation->fails()) {
            foreach ($validation->errors()->messages() as $field => $error) {
                $errors->add($field, $error[0]);
            }
        }

        // Must return null for inline validation to work
        return $errors;
    }

    /**
     * Helper function that filters a data array and only returns the fields that are defined in $rules.
     *
     * @param  array $data
     * @return array
     */
    public function sanitize($data, $rules = [])
    {
        if (empty($rules)) {
            $rules = $this->validationRules;
        }

        $allowedFields = array_filter($data, function($value, $key) use($rules) {
            return in_array($key, array_keys($rules));
        }, ARRAY_FILTER_USE_BOTH);

        $sanitizedFields = [];
        foreach ($allowedFields as $key => $value) {
            $rule = $rules[$key];

            // If field is not requred and $value is empty, ignore and let db handle default values
            if (strpos($rule, 'required') === false && $value === '') {
                continue;
            }

            $sanitizedFields[$key] = $value;

            if (strpos($rule, 'integer') !== false) {
                $sanitizedFields[$key] = (int) $value;
            }

            if (strpos($rule, 'numeric') !== false) {
                $sanitizedFields[$key] = str_replace(',', '.', $value);
            }

            if (strpos($rule, 'boolean') !== false) {
                $sanitizedFields[$key] = (bool) $value;
            }
        }

        return $sanitizedFields;
    }
}
