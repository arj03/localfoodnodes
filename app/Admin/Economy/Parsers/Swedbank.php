<?php

namespace App\Admin\Economy\Parsers;

use Validator;

class Swedbank
{
    private $file;

    private $columns = [
        'Clnr',
        'Kontonr',
        'Produkt',
        'Valuta',
        'Bokf dag',
        'Transdag',
        'Valutadag',
        'Referens',
        'Text',
        'Belopp',
        'Saldo'
    ];

    private $map = [
        'Bokf dag' => 'date',
        'Referens' => 'ref',
        'Text' => 'description',
        'Belopp' => 'amount',
    ];

    private $rules = [
        'Bokf dag' => 'required|date|date_format:Y-m-d',
        'Referens' => 'required|string',
        'Text' => 'required|string',
        'Belopp' => 'required|numeric',
    ];

    /**
     * [construct description]
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public function __construct($file) {
        $this->file = $file;
    }

    /**
     * [parse description]
     * @param  [type] $callback [description]
     * @return [type]           [description]
     */
    public function parse($callback) {
        $imported = [];
        $failed = [];

        if (($handle = fopen($this->file->getRealPath(), "r")) !== false) {
            while (($row = fgetcsv($handle, 0, "\t")) !== false) {
                $data = $this->validate($row);
                if ($data !== false) {
                    $res = $callback($data);

                    if ($res) {
                        $success[] = $data;
                    } else {
                        $failed[] = $data;
                    }
                }
            }

            fclose($handle);
        }

        return [$imported, $failed];
    }

    /**
     * [validate description]
     * @param  [type] $row [description]
     * @return [type]      [description]
     */
    public function validate($row) {
        // Make sure it's a valid row
        if (count($this->columns) !== count($row)) {
            return false;
        }

        // Build key value array
        $keyValue = array_combine($this->columns, $row);

        // Fix badly formatted integer
        $keyValue['Belopp'] = (int) str_replace(' ', '', $keyValue['Belopp']);

        $validation = Validator::make($keyValue, $this->rules);

        if ($validation->fails()) {
            return false;
        } else {
            return $this->mapKeys($keyValue);
        }
    }

    /**
     * [getHash description]
     * @param  [type] $row [description]
     * @return [type]      [description]
     */
    private function getHash($keyValue) {
        $values = array_values($keyValue);
        sort($values);

        return sha1(implode(',', $values));
    }

    /**
     * [getData description]
     * @param  [type] $row [description]
     * @return [type]      [description]
     */
    private function mapKeys($keyValue) {
        $data = [];

        foreach ($this->map as $oldKey => $newKey) {
            $data[$newKey] = utf8_encode($keyValue[$oldKey]);
        }

        $data['hash'] = $this->getHash($keyValue);

        return $data;
    }
}
