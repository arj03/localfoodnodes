<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Het :attribute moet geaccepteerd worden.',
    'active_url'           => 'Het :attribute is geen geldige URL.',
    'after'                => 'Het :attribute moet een datum zijn na :date.',
    'alpha'                => 'Het :attribute mag alleen letters bevatten.',
    'alpha_dash'           => 'Het :attribute mag alleen letters, nummers en koppeltekens bevatten.',
    'alpha_num'            => 'Het :attribute mag alleen letters en nummers bevatten.',
    'array'                => 'Het :attribute moet een rangschikking zijn.',
    'before'               => 'Het :attribute moet een datum voor :date zijn.',
    'between'              => [
        'numeric' => 'Het :attribute moet tussen :min en :max zijn.',
        'file'    => 'Het :attribute moet tussen :min en :max kilobytes zijn.',
        'string'  => 'Het :attribute moet tussen :min en :max karakters zijn.',
        'array'   => 'Het :attribute moet tussen :min en :max items bevatten.',
    ],
    'boolean'              => 'Het :attribute veld moet waar of onwaar zijn.',
    'confirmed'            => 'De :attribute bevestiging komt niet overeen.',
    'date'                 => 'Het :attribute is geen geldige datum.',
    'date_format'          => 'Het :attribute komt niet overeen met het bestandstype',
    'different'            => 'Het :attribute en :other moeten verschillend zijn.',
    'digits'               => 'Het :attribute moet :digits getallen zijn.',
    'digits_between'       => 'Het :attribute moet tussen :min en :max getallen zijn.',
    'dimensions'           => 'Het :attribute heeft ongeldige afbeeldingsafmetingen.',
    'distinct'             => 'Het :attribute heeft een dubbele waarde.',
    'email'                => 'Het :attribute moet een geldig e-mailadres zijn.',
    'exists'               => 'Het geselecteerde :attribute is ongeldig.',
    'file'                 => 'Het :attribute moet een bestand zijn.',
    'filled'               => 'Het :attribute is verplicht.',
    'image'                => 'Het :attribute moet een afbeelding zijn.',
    'in'                   => 'Het geselecteerde :attribute is ongeldig.',
    'in_array'             => 'Het :attribute veld bestaat niet in :other.',
    'integer'              => 'Het :attribute moet een heel getal zijn.',
    'ip'                   => 'Het :attribute moet een geldig IP adres zijn.',
    'json'                 => 'Het :attribute moet een geldige JSON-reeks zijn.',
    'max'                  => [
        'numeric' => 'Het :attribute mag niet groter zijn dan :max.',
        'file'    => 'Het :attribute mag niet groter zijn dan :max kilobytes.',
        'string'  => 'Het :attribute mag niet langer zijn dan :max karakters.',
        'array'   => 'Het :attribute mag niet meer dan :max items bevatten.',
    ],
    'mimes'                => 'Het :attribute moet het bestandstype :values zijn.',
    'mimetypes'            => 'Het :attribute moet het bestandstype :values zijn.',
    'min'                  => [
        'numeric' => 'Het :attribute moet ten minste :min zijn.',
        'file'    => 'Het :attribute moet ten minste :min kilobytes zijn.',
        'string'  => 'Het :attribute moet ten minste :min karakters bevatten.',
        'array'   => 'Het :attribute moet ten minste :min items bevatten.',
    ],
    'not_in'               => 'Het geselecteerde :attribute is ongeldig.',
    'numeric'              => 'Het :attribute moet een getal zijn.',
    'present'              => 'Het :attribute veld moet aanwezig zijn.',
    'regex'                => 'Het :attribute bestandstype is ongeldig.',
    'required'             => 'Verplicht.',
    'required_if'          => 'Het :attribute veld is verplicht wanneer :other is :value.',
    'required_unless'      => 'Het :attribute veld is verplicht tenzij :other in :values is.',
    'required_with'        => 'Het :attribute veld is verplicht wanneer :values aanwezig is.',
    'required_with_all'    => 'Het :attribute veld is verplicht wanneer :values aanwezig is.',
    'required_without'     => 'Het :attribute is verplicht wanneer :values afwezig is.',
    'required_without_all' => 'Het :attribute veld is verplicht wanneer geen van de :values aanwezig zijn.',
    'same'                 => 'Het :attribute en :other moeten met elkaar overeenkomen.',
    'size'                 => [
        'numeric' => 'Het :attribute moet :size zijn.',
        'file'    => 'Het :attribute moet :size kilobytes zijn.',
        'string'  => 'Het :attribute moet :size karakters zijn.',
        'array'   => 'Het :attribute moet :size items bevatten.',
    ],

    'string'               => 'Het :attribute moet een reeks zijn.',
    'timezone'             => 'Het :attribute moet een geldige zone zijn.',
    'unique'               => 'Het :attribute is al bezet.',
    'uploaded'             => 'Het :attribute is niet geupload.',
    'url'                  => 'Het :attribute is ongeldig.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],
];
