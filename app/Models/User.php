<?php

namespace App\Models;

use App\Traits\SaveTenantID;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Lab404\Impersonate\Models\Impersonate;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property string $region_code
 * @property string $phone
 * @property string $dob
 * @property string $email
 * @property string $password
 * @property int $available_as_freelancer
 * @property float $experience
 * @property string $job_title
 * @property string $about_me
 * @property int $country_id
 * @property int $state_id
 * @property int $city_id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[]
 *     $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAboutMe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvailableAsFreelancer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\City $city
 * @property-read \App\Models\Country $country
 * @property-read string $full_name
 * @property-read \App\Models\State $state
 * @property-read string $profile_image
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property string $language
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLanguage($value)
 */
class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use HasFactory, Notifiable, BelongsToTenant, InteractsWithMedia, SaveTenantID, HasRoles, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'region_code',
        'phone',
        'user_name',
        'dob',
        'email',
        'password',
        'available_as_freelancer',
        'experience',
        'job_title',
        'about_me',
        'email_verified_at',
        'country_id',
        'tenant_id',
        'state_id',
        'status',
        'city_id',
        'language',
        'region_code_flag',
    ];


    const LANGUAGES = [
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'de' => 'German',
        'ru' => 'Russian',
        'pt' => 'Portuguese',
        'ar' => 'Arabic',
        'zh' => 'Chinese',
        'tr' => 'Turkish',
        //        'it' => 'Italian'
    ];

    const PROFILE = 'profile';

    const FREELANCER_NO = 0;
    const FREELANCER_YES = 1;
    const AVAILABLE_AS_FREELANCER = [
        self::FREELANCER_YES => 'Yes',
        self::FREELANCER_NO  => 'No',
    ];

    const DEACTIVE = 0;
    const ACTIVE = 1;

    const STATUS = [
        self::ACTIVE   => 'Active',
        self::DEACTIVE => 'Deactive',
    ];

    protected $with = ['media'];

    protected $appends = ['full_name', 'profile_image'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'tenant_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static $rules = [
        'first_name' => 'required',
        'last_name'  => 'required',
        'email'      => 'required|email:filter|unique:users,email',
        'password'   => 'nullable|same:password_confirmation|min:6',
        'dob'        => 'nullable|date',
        'phone'      => 'required|unique:users,phone',
        'experience' => 'nullable|numeric|digits_between:1,2',
        'job_title'  => 'required',
        'about_me'   => 'required|max:400',
    ];

    public static $registerUserRules = [
        'first_name' => 'required',
        'last_name'  => 'required',
        'email'      => 'required|email:filter|unique:users,email',
        'password'   => 'nullable|same:password_confirmation|min:6',
        'user_name'  => 'required|alpha_num|unique:users,user_name|max:20',
    ];

    /**
     * @param $value
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     *
     * @return string
     */
    public function getProfileImageAttribute()
    {
        /** @var Media $media */
        $media = $this->getMedia(self::PROFILE)->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return asset('img/infyom-logo.png');
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * @return BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * @return BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    /**
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
