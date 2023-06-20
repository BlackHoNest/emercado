<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AESCipher;
use App\Http\Requests\Auth\RegisterRequest;

use App\Http\Controllers\GlobalList;

use App\Models\User;
use App\Models\Buyer;

class BuyerController extends Controller
{
    public function __construct(){
        $this->globalList = new GlobalList();
        $this->aes = new AESCipher();
    }

    public function buyer()
    {
        $Provinces = $this->globalList->Provinces();
        return view('guest.buyer', compact('Provinces'));
    }

    public function storebuyer(Request $request) {

        foreach(User::where(['username' => $request->username])->get() as $verify) {
            if($verify->username == $request->username) {
                $error = 'Username is already taken.';
                return redirect()->route('buyer.signup')->withErrors(['errors' => $error])->withInput($request->all());
            }
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:10 years ago',
            'gender' => 'required|string|max:6',
            'Province' => 'required|string|max:255',
            'Municipality' => 'required|string|max:255',
            'Barangay' => 'required|string|max:255',
            'street' => 'string|max:255',
            'ZipCode' => 'required|string|max:4',
            'username' => 'required|string|max:255',
            'contact_number' => 'required|numeric',
            'password' => 'required|string|max:255',
            'password_confirmation' => 'required|string|max:255',

        ]);

        $Province = (isset($request->Province)?$this->aes->decrypt($request->Province):"");
        $Municipality = (isset($request->Municipality)?$this->aes->decrypt($request->Municipality):"");
        $Barangay = (isset($request->Barangay)?$this->aes->decrypt($request->Barangay):"");

        $msg = "";

        if (strlen($request->password) < 8){
            $msg .= "Password must be 8 characters or more." .", ";
        }

        if (strlen($request->contact_number) != 11){
            $msg .= "Contact number must be 11 digit." .", ";
        }

        if (substr($request->contact_number, 0,2) != "09"){
            $msg .= "Contact number must start with 09" .", ";
        }

        if (empty($Province)){
            $msg .= "Please select Province" .", ";
        }

        if (empty($Municipality)){
            $msg .= "Please select Municipality" .", ";
        }

        if (empty($Barangay)){
            $msg .= "Please select Barangay" .", ";
        }

        if (isset($request->username)){
            foreach(User::where(['username' => $request->username])->get() as $verify) {
                if($verify->username == $request->username)
                $msg .= "Username is already taken" .", ";
            }
        }

        if ($request->password != $request->password_confirmation){
            $msg .= "Passwords did not matched" .", ";
        }
 
        $datetime = date('Ymd-His');

        $profilepictureName = \Str::slug($request->username.'-'.$datetime).'.'.$request->profile_photo->extension(); 
        $transferphoto = $request->file('profile_photo')->storeAs('public/images/client/photo', $profilepictureName);

        $saveseller = Buyer::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'contact_number' => $request->contact_number,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'province' => $Province,
            'municipality' => $Municipality,
            'barangay' => $Barangay,
            'street' => $request->street,
            'zipcode' => $request->ZipCode,
            'profile_picture' => $profilepictureName
        ]);

        Auth::login($user = User::create([
            'account_id' => $saveseller->id,
            'username' => $request->username,
            'user_type' => "buyer",
            'email' => null,
            'password' => Hash::make($request->password),
            'secretkey' => \Str::random(16),
        ]));

        event(new Registered($user));

        return redirect(RouteServiceProvider::HOME);

    }
}
