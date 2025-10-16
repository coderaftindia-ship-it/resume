<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SectionThree extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const FRONT_CMS_PATH = 'front_cms';
    /**
     * @var string[]
     */
    public static $rules = [
        'slider_text'          => 'required|string|max:200',
        'image_url'            => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'image_text_secondary' => 'required|string|max:20',
        'image_text'           => 'required|string|max:20',
    ];
    public static $messages = [
        'image_url.max'            => 'The :attribute file size should not be greater than 2 MB',
        'image_url.dimensions'     => 'The :attribute must be at least :min_width by :min_height pixels',
        's3_image_main.max'        => 'The :attribute file size should not be greater than 2 MB',
        's3_image_main.dimensions' => 'The :attribute must be at least :min_width by :min_height pixels',
    ];
    public static $customAttributes = [
        's3_text_title' => 'text title',
        's3_image_main' => 'image main',
    ];
    /**
     * @var string
     */
    protected $table = 'section_threes';
    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'slider_text',
        'image_text',
        'image_text_secondary',
        'image_url',
    ];
    /**
     * @var string[]
     */
    protected $casts = [
        'id'                   => 'integer',
        'slider_text'          => 'string',
        'image_text'           => 'string',
        'image_text_secondary' => 'string',
        'image_url'            => 'string',
    ];
}
