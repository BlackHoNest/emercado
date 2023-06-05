<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Buyer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;

class BuyerController extends Controller
{

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request)
    {
        $obj = new BuyerController;

        $profilepictureName = $request->username.'.'.$request->profile_photo->extension();  
     
        $request->profile_photo->move(public_path('client-images/profile/photo'), $profilepictureName);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        Buyer::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'address' => $request->address,
            'contact_number' => $request->phone_number,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'profile_picture' => $profilepictureName,
        ]);

        Auth::login($user = User::create([
            'account_id' => $obj->account_id($request->username),
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'user_type' => $request->user_type,
            'email' => null,
            'password' => Hash::make($request->password),
            'profile_picture' => $profilepictureName,
        ]));

        event(new Registered($user));

        return redirect(RouteServiceProvider::HOME);
    }

    protected function account_id($username) {

        $account_id = Buyer::where(['username' => $username])->get();

        foreach ($account_id as $id) return $id->id;

    }

}
