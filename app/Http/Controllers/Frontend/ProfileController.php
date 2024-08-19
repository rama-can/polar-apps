<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }
    /**
     * get user now for view profile
     */
    public function index()
    {
        $title = 'Profile';
        $user = auth()->user();

        return view('frontend.profile.index', compact('title', 'user'));
    }

    /**
     * update user profile
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $id = (int) auth()->user()->id;

        $result = $this->userService->update($request->all(), $id);

        if ($result['success']) {
            return redirect()->route('profile')->with('success', $result['message']);
        } else {
            return back()->withInput()->with('error', $result['message']);
        }
    }
}
