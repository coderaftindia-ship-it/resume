<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Models\User;
use App\Repositories\HomeRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Class WebController
 */
class WebController extends AppBaseController
{
    /**
     * @var HomeRepository
     */
    private $homeRepo;

    public function __construct(HomeRepository $homeRepo)
    {
        $this->homeRepo = $homeRepo;
        if (getLoggedInUser() != null) {
            $this->middleware(['role:admin', 'auth']);
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        if (getLoggedInUser() != null && ! getLoggedInUser()->hasRole('super_admin') && (getLoggedInUser()->user_name != request()->segment(2))) {
            return redirect()->route('portfolio.front', getLoggedInUser()->user_name);
        }
        $data = $this->homeRepo->getHomePageData();

        return view('web.index')->with($data);
    }

    /**
     * @return Factory|View
     */
    public function showRegister()
    {
        return view('web.registration.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function registerUser()
    {
        return view('web.registration.register');
    }

    /**
     * @param  Request  $request
     *
     * @return mixed
     */
    public function checkUserName(Request $request)
    {
        $checkName = $request->data;
        if (User::where('user_name', '=', $checkName)->exists()) {
            return $this->sendError('User name already exists.');
        } else {
            return $this->sendSuccess('Username available.');
        }
    }
}
