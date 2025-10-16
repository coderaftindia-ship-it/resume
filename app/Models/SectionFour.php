<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SectionFour extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * @var string 
     */
    protected $table = 'section_fours';

    /**
     * @var string[] 
     */
    protected $fillable = [
        'id',
        'image_url',
        'image_text',
        'image_text_description',
        'color',
    ];

    public const FRONT_CMS_PATH = 'front_cms';

    /**
     * @var string[]
     */
    public static $rules = [
        'image_text'             => 'required|string|max:30',
        'image_text_description' => 'required|string|max:200',
        'image_url'              => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'color'                  => 'required',
    ];

    /**
     * @var string[] 
     */
    public static $customAttributes = [   
        's4_text_title'          => 'text title',
        's4_text_secondary'      => 'text secondary',
    ];

    /**
     * @var string[] 
     */
    public static $messages = [
        'image_url.max'          => 'The :attribute file size should not be greater than 2 MB',
        'image_url.dimensions'   => 'The :attribute must be at least :min_width by :min_height pixels',
    ];
}
