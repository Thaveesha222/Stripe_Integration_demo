<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * show user profile of logged user
     */
    public function show()
    {
        return view('show',auth()->user());
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * show profile by id
     */
    public function showOne($id)
    {
        return view('profiles',['user'=>User::with('stripeGateway')->find($id)]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * show all profiles
     */
    public function showAll()
    {
        return view('allprofiles',['users'=>User::all()]);
    }
}
