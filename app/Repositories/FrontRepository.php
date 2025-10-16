<?php

namespace App\Repositories;

use App\Models\AdminSetting;
use App\Models\FrontCms;
use App\Models\SectionFour;
use App\Models\SectionFive;
use App\Models\SectionThree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Class FrontRepository
 */
class FrontRepository extends BaseRepository
{
    /**
     * @return void
     */
    public function getFieldsSearchable()
    {
        //
    }

    /**
     * @return string
     */
    public function model()
    {
        return FrontCms::class;
    }

    /**
     * @return array
     */
    public function getFrontScreenData()
    {
        $data = null;
        if (request()->route()->getName() == 'cms.section.one.index') {
            $data['sectionOne'] = FrontCms::toBase()->where('type', FrontCms::SECTION_ONE)->pluck('value', 'key');
            $data['viewName'] = 'front.section_one.index';
        }
        if (request()->route()->getName() == 'cms.section.two.index') {
            $data['sectionTwo'] = FrontCms::toBase()->where('type', FrontCms::SECTION_TWO)->pluck('value', 'key');
            $data['viewName'] = 'front.section_two.index';
        }

        return $data;
    }

    /**
     * @param  array  $input
     *
     * @return array
     */
    public function updateCms($input)
    {
        $cmsInputArray = Arr::except($input, ['_token', '_method']);
        if ($input['section'] == FrontCms::SECTION_ONE) {
            $this->updateSection($cmsInputArray);

            return ['status'   => true, 'message' => 'Section One updated successfully',
                    'redirect' => 'cms.section.one.index',
            ];
        }
        if ($input['section'] == FrontCms::SECTION_TWO) {
            $this->updateSection($cmsInputArray);

            return [
                'status'   => true, 'message' => 'Section Two updated successfully',
                'redirect' => 'cms.section.two.index',
            ];
        }
    }

    /**
     * @param  array  $inputData
     *
     * @return bool
     */
    public function updateSection($inputData)
    {
        foreach ($inputData as $key => $value) {
            $frontCmsSetting = FrontCms::where('key', $key)->first();
            if (! $frontCmsSetting) {
                continue;
            }
            if (in_array($key, ['s2_background_image']) && ! empty($value)) {
                $this->fileUpload($frontCmsSetting, $value);
                continue;
            }
            $frontCmsSetting->update(['value' => $value]);
        }

        return true;
    }

    /**
     * @param $frontCmsSetting
     *
     * @param $file
     *
     * @return mixed
     */
    public function fileUpload($frontCmsSetting, $file)
    {
        if (! empty($frontCmsSetting->getMedia(FrontCms::FRONT_CMS_PATH)->first())) {
            $frontCmsSetting->clearMediaCollection(FrontCms::FRONT_CMS_PATH);
        }
        $media = $frontCmsSetting->addMedia($file)->toMediaCollection(FrontCms::FRONT_CMS_PATH,
            config('app.media_disc'));
        $frontCmsSetting->update(['value' => $media->getUrl()]);

        return $frontCmsSetting;
    }

    /**
     * @param  array  $input
     *
     * @return bool
     */
    public function storeSectionThreeData($input)
    {
        $sectionThree = SectionThree::create(Arr::except($input, ['_token', 'section', 'image_url']));
        $media = $sectionThree->addMedia($input['image_url'])->toMediaCollection(SectionThree::FRONT_CMS_PATH,
            config('app.media_disc'));
        $sectionThree->update(['image_url' => $media->getUrl()]);

        return true;
    }

    /**
     * @param  array  $input
     *
     * @return bool
     */
    public function storeSectionFourData($input)
    {
        $sectionFour = SectionFour::create(Arr::except($input, ['_token', 'section', 'image_url']));
        $media = $sectionFour->addMedia($input['image_url'])->toMediaCollection(SectionFour::FRONT_CMS_PATH,
            config('app.media_disc'));
        $sectionFour->update(['image_url' => $media->getUrl()]);

        return true;
    }
    
    /**
     * @param  array  $input
     *
     * @return bool
     */
    public function storeSectionFiveData(array $input)
    {
        SectionFive::create($input);
        
        return true;
    }
    
    /**
     * @return mixed
     */
    public function getSectionThreeData()
    {
        $data['sectionThreeFront'] = FrontCms::where('type', FrontCms::SECTION_THREE)->pluck('value', 'key')->toArray();

        return $data;
    }
    
    /**
     * @return mixed
     */
    public function getSectionFiveData()
    {
        $data['sectionFiveFront'] = FrontCms::where('type', FrontCms::SECTION_FIVE)->pluck('value', 'key')->toArray();

        return $data;
    }


    /**
     * @return array
     */
    public function getSectionFourData()
    {
        $data['sectionFourFront'] = FrontCms::where('type', FrontCms::SECTION_FOUR)->pluck('value', 'key')->toArray();

        return $data;
    }
    
    /**
     * @param  array  $input
     * @param  SectionThree  $sectionThree
     *
     * @return bool
     */
    public function updateSectionThreeData($input, $sectionThree)
    {
        $frontCmsData = Arr::only($input, ['s3_text_title', 's3_image_main']);
        foreach ($frontCmsData as $key => $value) {
            $frontCmsSetting = FrontCms::where('key', $key)->first();
            if (! $frontCmsSetting) {
                continue;
            }
            if ($key === 's3_image_main' && ! empty($value)) {
                $this->fileUpload($frontCmsSetting, $value);
                continue;
            }
            $frontCmsSetting->update(['value' => $value]);
        }

        $sectionThreeData = Arr::only($input, ['image_text', 'image_text_secondary', 'slider_text']);
        $sectionThree->update($sectionThreeData);
        if (! empty($input['image_url'])) {
            $sectionThree->clearMediaCollection(SectionThree::FRONT_CMS_PATH);
            $media = $sectionThree->addMedia($input['image_url'])->toMediaCollection(SectionThree::FRONT_CMS_PATH,
                config('app.media_disc'));
            $sectionThree->update(['image_url' => $media->getUrl()]);
        }

        return true;
    }

    /**
     * @param  array  $input
     * 
     * @param $sectionFour
     * 
     * @return bool
     */
    public function updateSectionFourData($input, $sectionFour)
    {
        $frontCmsData = Arr::only($input, ['s4_text_title', 's4_text_secondary']);
        
        foreach ($frontCmsData as $key => $value) {
            $frontCmsSetting = FrontCms::where('key', $key)->first();
            
            if (! $frontCmsSetting) {
                continue;
            }
            
            $frontCmsSetting->update(['value' => $value]);
        }

        $sectionFourData = Arr::only($input, ['image_text', 'image_text_description', 'color']);
        $sectionFour->update($sectionFourData);
        
        if (! empty($input['image_url'])) {
            $sectionFour->clearMediaCollection(SectionFour::FRONT_CMS_PATH);
            $media = $sectionFour->addMedia($input['image_url'])->toMediaCollection(SectionFour::FRONT_CMS_PATH,
                config('app.media_disc'));
            $sectionFour->update(['image_url' => $media->getUrl()]);
        }

        return true;
    }
    /**
     * @param  array  $input
     * 
     * @param $sectionFive
     * 
     * @return bool
     */
    public function updateSectionFiveData($input, $sectionFive)
    {
        $frontCmsData = Arr::only($input, ['s5_text_title', 's5_main_image']);
        foreach ($frontCmsData as $key => $value) {
            $frontCmsSetting = FrontCms::where('key', $key)->first();
            if (! $frontCmsSetting) {
                continue;
            }
            if ($key === 's5_main_image' && ! empty($value)) {
                $this->fileUpload($frontCmsSetting, $value);
                continue;
            }
            $frontCmsSetting->update(['value' => $value]);
        }

        $sectionFiveData = Arr::only($input, ['text_main', 'text_secondary']);
        $sectionFive->update($sectionFiveData);

        return true;
    }
    /**
     * @return array
     */
    public function prepareLandingScreenData()
    {
        $data['sectionOne'] = FrontCms::toBase()->where('type', FrontCms::SECTION_ONE)->pluck('value', 'key');
        $data['sectionTwo'] = FrontCms::toBase()->where('type', FrontCms::SECTION_TWO)->pluck('value', 'key');

        $data['sectionThreeFrontCms'] = FrontCms::toBase()->where('type', FrontCms::SECTION_THREE)->pluck('value',
            'key');
        $data['sectionFourFrontCms'] = FrontCms::toBase()->where('type', FrontCms::SECTION_FOUR)->pluck('value',
            'key');
        $data['sectionFiveFrontCms'] = FrontCms::toBase()->where('type', FrontCms::SECTION_FIVE)->pluck('value',
            'key');
        $data['sectionFiveData'] = SectionFive::latest()->take(4)->get();
        $data['sectionThreeData'] = SectionThree::toBase()->get();
        $data['sectionFourData'] = SectionFour::latest()->take(6)->get();

        $data['sectionFour'] = FrontCms::toBase()->where('type', FrontCms::SECTION_FOUR)->pluck('value', 'key');
        $data['sectionFive'] = FrontCms::toBase()->where('type', FrontCms::SECTION_FIVE)->pluck('value', 'key');
        $data['sectionSix'] = FrontCms::toBase()->where('type', FrontCms::SECTION_SIX)->pluck('value', 'key');
        $data['generalSettings'] = AdminSetting::toBase()->where('type', AdminSetting::GENERAL)->pluck('value', 'key');
        $data['socialSettings'] = AdminSetting::toBase()->where('type', AdminSetting::SOCIAL_SETTING)->pluck('value', 'key');
        

        return $data;
    }

    /**
     * @return array
     */
    public function privacyPolicyAndTermConditionsData()
    {
        $data['generalSettings'] = AdminSetting::toBase()->where('type', AdminSetting::GENERAL)->pluck('value', 'key');
        $data['sectionSix'] = FrontCms::toBase()->where('type', FrontCms::SECTION_SIX)->pluck('value', 'key');
        $data['socialSettings'] = AdminSetting::toBase()->where('type', AdminSetting::SOCIAL_SETTING)->pluck('value',
            'key');

        return $data;
    }
}
