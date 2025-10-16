<?php

namespace App\Repositories;

use App\Models\Achievement;
use App\Models\AdminEnquiry;
use App\Models\Blog;
use App\Models\Education;
use App\Models\Enquiry;
use App\Models\Experience;
use App\Models\PricingPlan;
use App\Models\RecentWorkType;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\User;

/**
 * Class HomeRepository
 */
class HomeRepository
{
    /**
     * @return mixed
     */
    public function getHomePageData()
    {
        $data['skills'] = Skill::toBase()->orderBy('created_at', 'DESC')->get();
        $data['aboutMe'] = Achievement::toBase()->orderBy('created_at', 'DESC')->get();
        $data['pricingPlans'] = PricingPlan::with(['planAttributes', 'media', 'currency'])->orderBy('created_at',
            'DESC')->take(3)->get();
        $data['services'] = Service::orderBy('created_at', 'DESC')->get();
        $data['user'] = getUser()->with(['city', 'country'])->first();
        $data['socialSettings'] = Setting::toBase()->where('type', Setting::SOCIAL_SETTING)->get();
        $data['testimonials'] = Testimonial::with('media')->orderBy('created_at', 'DESC')->get();
        $data['experiences'] = Experience::with(['country', 'city', 'state'])->orderBy('created_at',
            'DESC')->take(3)->get();
        $data['educations'] = Education::with(['country', 'city', 'state'])->orderBy('created_at',
            'DESC')->take(3)->get();
        $data['recentWorksType'] = RecentWorkType::with('recentWorks')->withCount('recentWorks')->having('recent_works_count',
            '>', 0)->orderBy('created_at', 'DESC')->take(5)->get();
        $data['blogs'] = Blog::with(['category', 'media'])->where('is_published',
            Blog::PUBLISHED)->orderBy('created_at', 'DESC')->take(3)->get();

        return $data;
    }

    /**
     * @return mixed
     */
    public function getAdminDashboardData()
    {
        $data['activeUsers'] = User::where('status', User::ACTIVE)->with('roles')->whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->count();
        $data['deActiveUsers'] = User::where('status', User::DEACTIVE)->with('roles')->whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->count();
        $data['freelancersUsers'] = User::where('status', User::ACTIVE)->where('available_as_freelancer',
            User::FREELANCER_YES)->with('roles')->whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->count();
        $data['publishedBlogs'] = Blog::where('is_published', Blog::PUBLISHED)->count();
        $data['featuredBlogs'] = Blog::where('is_featured', Blog::FEATURED)->count();
        $data['enquiries'] = AdminEnquiry::where('status', AdminEnquiry::UNREAD)->count();
        $data['pricingPlans'] = PricingPlan::count();
        $data['testimonials'] = Testimonial::count();
        $data['Enquiries'] = AdminEnquiry::latest()->take(5)->get();
        $data['users'] = User::latest()->with('roles')->whereHas('roles', function ($q) {
            $q->where('name', 'admin');
            $q->where('status', User::ACTIVE);
        })->take(5)->get();

        return $data;
    }
}
