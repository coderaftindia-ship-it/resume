<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SectionFive extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const FRONT_CMS_PATH = 'front_cms';

    /**
     * @var string[]
     */
    public static $rules = [
        'text_main'      => 'required|string|max:40',
        'text_secondary' => 'required|string|max:200',
    ];

    /**
     * @var string[]
     */
    public static $messages = [
        's5_main_image.max'        => 'The :attribute file size should not be greater than 2 MB',
        's5_main_image.dimensions' => 'The :attribute must be at least :min_width by :min_height pixels',
    ];

    /**
     * @var string[]
     */
    public static $customAttributes = [
        's5_text_title' => 'text title',
        's5_main_image' => 'image main',
    ];

    /**
     * @var string
     */
    protected $table = 'section_fives';
    /**
     * @var string[]
     */

    protected $fillable = [
        'id',
        'text_main',
        'text_secondary',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'id'             => 'integer',
        'text_main'      => 'string',
        'text_secondary' => 'string',
    ];
}
