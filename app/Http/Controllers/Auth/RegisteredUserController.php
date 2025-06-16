<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AppPage;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller {

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        $app_page = AppPage::select(['app_pages.description'])->where('id', 3)->first();
        return view('auth.register', compact("app_page"));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {
        $request->validate([
            'name'            => 'required|string|min:2|max:255',
            'phone'           => 'required|string|min:10|max:255',
            'email'           => 'required|string|email|min:6|max:255|unique:users',
            'password'        => 'required|string|min:6',
            'company_name'    => 'required|string|min:2|max:255|unique:users',
            'license_no'      => 'required|string|min:2|max:255',
            'license_expiry'  => 'required|string|min:2|max:255',
            'principal_place' => 'required|string|min:2|max:255',
            'activities'      => 'required|string|min:2|max:255',
            'address'         => 'required|string|min:2|max:255',
                /* 'website' => 'required|string|min:2|max:255', */
        ]);

        $Model_Data = new User();

        $Model_Data->company_name = $request->company_name;
        $Model_Data->name = $request->name;
        $Model_Data->email = $request->email;
        $Model_Data->password = Hash::make($request->password);
        $Model_Data->phone = $request->phone;
        $Model_Data->license_no = $request->license_no;
        $Model_Data->license_expiry = $request->license_expiry;
        $Model_Data->principal_place = $request->principal_place;
        $Model_Data->address = $request->address;
        $Model_Data->lat = $request->latitude;
        $Model_Data->lng = $request->longitude;
        /* $Model_Data->website = $request->website;	 */
        $Model_Data->activities = $request->activities;
        $Model_Data->status = 0;
        $Model_Data->application_status = 1;
        $Model_Data->approval_status = 0;
        //$Model_Data->email_verified = 0;

        $Model_Data->save();
        return redirect()->route('sellerSignup');

        /* Auth::login($user = User::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password),
          ]));

          event(new Registered($user));

          return redirect(RouteServiceProvider::HOME); */
    }

    public function sellerSignup() {
        return view('auth.registration-pending');
    }
}
