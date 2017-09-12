<?php

namespace App\Image;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Database\Eloquent\Collection;

use App\BaseModel;

class Image extends BaseModel
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'entity_id' => 'required',
        'entity_type' => 'required',
        'image' => 'mimes:jpeg,png,gif|max:10000',
        'sort' => ''
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entity_id',
        'entity_type',
        'file',
        'sort'
    ];

    /**
     * Image sizes.
     */
    private $sizes = [
        'small' => 400,
        'medium' => 800,
        'large' => 1200
    ];

    /**
     * Lifecycle events.
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($image) {
            $filenames = $image->getFilenames();
            $filenames->each(function($filename) {
                Storage::disk('s3')->delete($filename);
            });
        });

        static::creating(function($image) {
            $image->file = $image->upload($image->file);
        });
    }

    /**
     * Upload image to AWS S3.
     *
     * @param Illuminate\Http\UploadedFile $file
     * @param array $contextKeys
     * @return string filename
     */
    public function upload($file)
    {
        $filenames = $this->generateFilenames($file);

        foreach ($this->sizes as $size => $width) {
            $filename = $filenames->get($size);
            $image = InterventionImage::make($file)->orientate();

            if ($image->height() > $image->width()) {
                // Portrait
                $image->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                // Landscape
                $image->resize(null, $width, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $image->crop($width, $width);
            $image->encode('jpg', 100);
            $image = $image->stream();
            Storage::disk('s3')->put($filename, $image->__toString(), 'public');
        }

        return $filenames->get('original');
    }

    /**
     * Generate image name for upload.
     *
     * @param Illuminate\Http\UploadedFile $file
     * @param array $keys array with context keys and values, i.e. ['user' => $userId]
     * @param string $sizeName
     * @return string generated filename
     */
    private function generateFilenames($file)
    {
        $date = date('YmdHi');
        $org = preg_replace('/[^a-zA-Z0-9]/', '_', $file->getClientOriginalName());

        $filenames = new Collection();
        $filenames->put('original', $date . '_' . strtolower($org) . '.' . $file->getClientOriginalExtension());
        foreach ($this->sizes as $size => $width) {
            $filename = $date . '_' . strtolower($org) . '_' . $size . '.' . $file->getClientOriginalExtension();
            $filenames->put($size, $filename);
        }

        return $filenames;
    }

    /**
     * Get all filenames for all sizes.
     *
     * @return Collection
     */
    public function getFilenames()
    {
        $filenames = new Collection();
        foreach ($this->sizes as $size => $width) {
            $filenames->add($this->getFilename($size));
        }

        return $filenames;
    }

    /**
     * Get filename.
     *
     * @param string $size
     * @return string
     */
    public function getFilename($size)
    {
        return $this->getBasename($size) . $this->getExtension();
    }

    /**
     * Get extension from filename.
     *
     * @param string $filename
     * @return string
     */
    public function getExtension($dot = true)
    {
        $extension = pathinfo($this->file, PATHINFO_EXTENSION);

        return $dot ? '.' . $extension : $extension;
    }

    /**
     * Get basename.
     *
     * @param string $size
     * @return stirng
     */
    public function getBasename($size = '')
    {
        $basename = str_replace($this->getExtension(), '', $this->file);

        if ($size) {
            $basename .= '_' . $size;
        }

        return $basename;
    }

    /**
     * Get image url.
     *
     * @param string $filename
     * @param string $size large, medium or small
     * @return string url
     */
    public function url($size = 'medium')
    {
        $filename = $this->getBasename($size) . $this->getExtension();

        if (Storage::disk('s3')->exists($filename)) {
            return Storage::disk('s3')->url($filename);
        }
    }
}
