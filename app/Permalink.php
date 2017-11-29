<?php

namespace App;

class Permalink extends BaseModel
{
    public $timestamps = false;

    protected $appends = ['url'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'entity_id' => 'required',
        'entity_type' => 'required',
        'slug' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entity_id',
        'entity_type',
        'slug',
    ];

    /**
     * Lifecycle events.
     */
    protected static function boot() {
        parent::boot();

        static::saving(function($permalink) {
            $permalink->slug = self::generateSlug($permalink->id, $permalink->slug);
        });
    }

    /**
     * Generate the permalink name.
     *
     * @param string $string
     * @param integer $int Count of the iterations needed to generate a unique name.
     * @return string
     */
    public static function generateSlug($permalinkId, $string, $int = 1)
    {
        // Replace non letter or digits by -
        $slug = preg_replace('~[^\pL\d]+~u', '-', $string);
        // Convert special chars
        $pattern = array('é','è','ë','ê','É','È','Ë','Ê','á','à','ä','â','å','Á','À','Ä','Â','Å','ó','ò','ö','ô','Ó','Ò','Ö','Ô','í','ì','ï','î','Í','Ì','Ï','Î','ú','ù','ü','û','Ú','Ù','Ü','Û','ý','ÿ','Ý','ø','Ø','œ','Œ','Æ','ç','Ç');
        $replace = array('e','e','e','e','E','E','E','E','a','a','a','a','a','A','A','A','A','A','o','o','o','o','O','O','O','O','i','i','i','I','I','I','I','I','u','u','u','u','U','U','U','U','y','y','Y','o','O','a','A','A','c','C');
        $slug = str_replace($pattern, $replace, $slug);
        // Remove unwanted characters
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        // Trim
        $slug = trim($slug, '-');
        // Remove duplicate -
        $slug = preg_replace('~-+~', '-', $slug);
        // Lowercase
        $slug = strtolower($slug);

        if (empty($slug)) {
            return false;
        }

        if ($int > 1) {
            $slug = $slug . '-' . $int;
        }

        // If permalink name exists call self again to generate new name
        $exists = (bool) Permalink::where('slug', $slug)->where('id', '!=', $permalinkId)->first();
        if ($exists) {
            $int += 1;
            return self::generateSlug($permalinkId, $string, $int);
        }

        return $slug;
    }

    /**
     * Get url.
     */
    public function getUrlAttribute()
    {
        return '/' . $this->entity_type . '/' . $this->slug;
    }

    /**
     * Get url without preceding slash for use with Request::is.
     */
    public function getUrlWithoutSlashAttribute()
    {
        return $this->entity_type . '/' . $this->slug;
    }
}
