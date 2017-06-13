<?php

namespace App\Node;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Reko extends \App\BaseModel
{
    protected $table = 'reko';

    protected $appends = ['location'];

    // /**
    //  * Validation rules.
    //  *
    //  * @var array
    //  */
    // protected $validationRules = [
    //     'name' => 'required',
    //     'link' => 'required',
    //     'location' => 'required',
    // ];
    //
    // /**
    //  * The attributes that are mass assignable.
    //  * @var array
    //  */
    // protected $fillable = [
    //     'name',
    //     'link',
    //     'location',
    // ];

    /**
     * Add location select to query.
     */
    public function newQuery($excludeDeleted = true)
    {
        $raw = 'astext(location) as location';
        return parent::newQuery($excludeDeleted)->addSelect('*', DB::raw($raw));
    }

    /**
     * Get location.
     */
    public function getLocationAttribute()
    {
        if (isset($this->attributes['location'])) {
            $location = substr($this->attributes['location'], 6);
            $location = substr($location, 0, strpos($location, ')'));
            list ($lat, $lng) = explode(' ', $location);

            return ['lat' => $lat, 'lng' => $lng];
        }
    }
}
