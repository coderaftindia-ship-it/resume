<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCmsRequest;
use App\Repositories\FrontRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laracasts\Flash\Flash;

class FrontController extends Controller
{
    /**
     * @var FrontRepository
     */
    private $frontRepo;

    /**
     * @param  FrontRepository  $frontRepo
     */
    public function __construct(FrontRepository $frontRepo)
    {
        $this->frontRepo = $frontRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $data = $this->frontRepo->getFrontScreenData();

        return view($data['viewName'])->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UpdateCmsRequest  $request
     *
     * @return RedirectResponse
     */
    public function update(UpdateCmsRequest $request)
    {
        $input = $request->all();
        $cmsData = $this->frontRepo->updateCms($input);
        Flash::success($cmsData['message']);

        return redirect()->route($cmsData['redirect']);
    }

    // Landing screen functions

    /**
     * @return Application|Factory|View
     */
    public function showLandingScreen()
    {
        $data = $this->frontRepo->prepareLandingScreenData();

        return view('front.index')->with($data);
    }

    /**
     * @return Application|Factory|View
     */
    public function termsAndConditions()
    {
        $data = $this->frontRepo->privacyPolicyAndTermConditionsData();
        
        return view('front.terms_conditions.index')->with($data);
    }

    /**
     * @return Application|Factory|View
     */
    public function privacyPolicy()
    {
        $data = $this->frontRepo->privacyPolicyAndTermConditionsData();
        
        return view('front.privacy_policy.index')->with($data);
    }
}
