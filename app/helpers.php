<?php

use App\Models\AdminSetting;
use App\Models\City;
use App\Models\Setting;
use App\Models\State;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @param $countryId
 *
 * @return array
 */
function getStates($countryId)
{
    return State::toBase()->where('country_id', $countryId)->pluck('name', 'id')->toArray();
}

/**
 * @param $stateId
 *
 * @return array
 */
function getCities($stateId)
{
    return City::toBase()->where('state_id', $stateId)->pluck('name', 'id')->toArray();
}

/**
 * @param $phone
 * @param $regionCode
 * @return string|null
 */
function preparePhoneNumber($phone, $regionCode)
{
    return (! empty($phone)) ? '+'.$regionCode.' '.$phone : null;
}

/**
 * @param $key
 *
 * @return mixed
 */
function getSettingValue($key)
{
    if (getLoggedInUser() != null && getLoggedInUser()->hasRole('super_admin')) {
        return AdminSetting::toBase()->where('key', $key)->value('value');
    } else {
        return Setting::toBase()->where('key', $key)->value('value');
    }
}

/**
 * @param $key
 *
 * @return mixed
 */
function getAdminSettingValue($key)
{
    return AdminSetting::toBase()->where('key', $key)->value('value');
}

/**
 * @return mixed
 */
function getAppName()
{
    /** @var Setting $appName */
    static $appName;

    if (empty($appName)) {
        if (getLoggedInUser() != null && getLoggedInUser()->hasRole('super_admin')) {
            $appName = AdminSetting::where('key', 'company_name')->first();
        } else {
            $appName = Setting::where('key', 'company_name')->first();
        }
    }

    return $appName->value;

}

/**
 * @return mixed
 */
function getLogoUrl()
{
    static $companyLogo;

    if (empty($companyLogo)) {
        if (getLoggedInUser() != null && getLoggedInUser()->hasRole('super_admin')) {
            $companyLogo = AdminSetting::where('key', '=', 'company_logo')->first();
        } else {
            $companyLogo = Setting::where('key', '=', 'company_logo')->first();
        }
    }

    return $companyLogo->value;

}

/**
 * @return mixed
 */
function getWebsiteUrl()
{
    static $companyWebsite;

    if (empty($companyWebsite)) {
        if (getLoggedInUser() != null && getLoggedInUser()->hasRole('super_admin'))
            $companyWebsite = AdminSetting::where('key', '=', 'website')->first();
        else
            $companyWebsite = Setting::where('key', '=', 'website')->first();
    }

    return $companyWebsite->value;

}

/**
 * @param $input
 * 
 * @return array
 */
function prepareInputArray($input)
{
    $item = [];
    foreach ($input as $key => $data) {
        foreach ($data as $index => $value) {
            $item[$index][$key] = $value;
        }
    }

    return $item;
}

function getCurrentLanguageName()
{
    return User::whereId(Auth::id())->first()->language;
}

function getUserLanguages()
{
    return User::LANGUAGES;
}

function checkRequest($request)
{
    if (Request::is($request)) {
        return true;
    }

    return false;
}
function getUserDate()
{
    return Auth::user()->dob;
}

/**
 * @param $startDate
 * @param $endDate
 *
 * @return string
 */
function formatStartAndEndDate($startDate, $endDate)
{
    $years = $startDate->diffInYears($endDate);
    $months = $startDate->diffInMonths($endDate);
    $days = $startDate->diffInDays($endDate);
    
    $totalYears = '';
    $totalMonths = '';
    $totalDays = '';
    if ($years != 0){
        $totalYears = $years.' '.'Year';
    }
    if ($years > 0 && $months > 11){
        $months = $months - 12 * $years;
    }
    if ($months != 0) {
        $totalMonths = $months.' '.'Months';
    }
    if ($years == 0 && $months == 0 && $days > 0) {
        $totalDays = $days.' '.'Days';
    }

    return $totalYears.' '.$totalMonths.' '.$totalDays;
}

/**
 * @return int
 */
function getLoggedInUserId()
{
    return Auth::id();
}

/**
 * @return Authenticatable
 */
function getLoggedInUser()
{
    return Auth::user();
}

/**
 * @return User
 */
function getUser()
{
    static $user = null;
    if (getLoggedInUser() == null) {
        $user = User::where('user_name', request()->segment(2))->first();
    } else {
        $user = getLoggedInUser();
    }

    return $user;
}

/**
 * @return mixed
 */
function getAdminUserName() {
    $user = DB::table('users')->where('user_name', request()->segment(2))->first();
    
    return $user->first_name .' '. $user->last_name;
}


/**
 * @param  array  $models
 * @param  string  $columnName
 * @param  int  $id
 *
 * @return bool
 */
function canDelete($models, $columnName, $id)
{
    foreach ($models as $model) {
        $result = $model::where($columnName, $id)->exists();
        if ($result) {
            return true;
        }
    }

    return false;
}
