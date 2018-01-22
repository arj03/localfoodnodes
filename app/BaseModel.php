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

            $sanitizedFields[$key] = $this->removeEmoji($value);

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

    private function removeEmoji($string) {
        return preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $string);
    }
}
