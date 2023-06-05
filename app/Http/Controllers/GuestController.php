<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AESCipher;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

use App\Http\Controllers\GlobalList;
use App\Models\FarmType;
use App\Models\FarmTypeSub;
use App\Models\Aid;
use App\Models\Seller;
use App\Models\Farm;
use App\Models\User;

class GuestController extends Controller
{

    protected $globalList;
    protected $aes;

    public function __construct(){
        $this->globalList = new GlobalList();
        $this->aes = new AESCipher();
    }

    public function seller(Request $request)
    {
        $aids = Aid::orderBy("AidName", "ASC")->get();
        $farmTypes = FarmType::orderby("description", "ASC")->get();
        $Municipalties = $this->globalList->Municipalities(864);
        return view('guest.seller', compact('Municipalties','farmTypes','aids'));
    }

    public function buyer(Request $request)
    {
        return view('guest.buyer');
    }

    public function storeseller(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:10 years ago',
            'gender' => 'required|string|max:6',
            'civil_status' => 'required|string|max:15',
            'education' => 'required|string|max:20',
            'Municipality' => 'required|string|max:255',
            'Barangay' => 'required|string|max:255',
            'street' => 'string|max:255',
            'ZipCode' => 'required|string|max:4',
            'username' => 'required|string|max:255',
            'contact_number' => 'required|numeric',
            'password' => 'required|string|max:255',
            'password_confirmation' => 'required|string|max:255',
            'idnumber' => 'required|string|max:255',
            'farm_municipal' => 'required|string|max:255',
            'farm_barangay' => 'required|string|max:255',
            'farm_size' => 'required|string|max:255',

        ]);
        // 

        $Municipality = (isset($request->Municipality)?$this->aes->decrypt($request->Municipality):"");
        $Barangay = (isset($request->Barangay)?$this->aes->decrypt($request->Barangay):"");
        $farm_municipal = (isset($request->farm_municipal)?$this->aes->decrypt($request->farm_municipal):"");
        $farm_barangay = (isset($request->farm_barangay)?$this->aes->decrypt($request->farm_barangay):"");
        
        
        $msg = "";

        if (strlen($request->contact_number) != 11){
            $msg .= "Contact number must be 11 digit." .", ";
        }

        if (substr($request->contact_number, 0,2) != "09"){
            $msg .= "Contact number must start with 09" .", ";
        }

        if (!$request->hasFile('valid_ID')) {
            $msg .= "Please select a valid ID" .", ";
        }
        
        if (!$request->hasFile('profile_photo')) {
            $msg .= "Please select a valid profile photo" .", ";
        }

        if (empty($Municipality)){
            $msg .= "Please select Municipality" .", ";
        }

        if (empty($Barangay)){
            $msg .= "Please select Barangay" .", ";
        }

        if ($request->password != $request->password_confirmation){
            $msg .= "Passwords did not matched" .", ";
        }

        if (empty($farm_municipal)){
            $msg .= "Please select your farm municipality" .", ";
        }

        if (empty($farm_barangay)){
            $msg .= "Please select your farm barangay" .", ";
        }


        if (!isset($request->FarmType)){
            $msg .= "Please select type of farm" .", ";
        }   

        if (isset($request->FarmType)){
            $naa = false;
            foreach($request->FarmType as $ftype){
                $id = $this->aes->decrypt($ftype);
                $exTypeSubs = FarmTypeSub::where("farmtypeid", $id)->get();
                foreach($exTypeSubs as $sub){
                    $name = \Str::slug($sub->description);
                    if (isset($request->$name)){
                        $naa = true;
                        $gross = $name."-harvest_gross";
                        $net = $name."-harvest_net";
                        // dd($request->$gross);
                        if (empty($request->$name."-harvest_gross")){
                            $msg .= "Please select estimated yield per havest (gross) of product " .$sub->description .", ";
                        }
                        if (empty($request->$net)){
                            $msg .= "Please select estimated yield per havest (net) of product " .$sub->description .", ";
                        }
                    }
                }
                
            }

            if (!$naa){
                $msg .= "Please select product(s) of selected farm type" .", ";
            }
        }

        // dd($msg);
        if (!empty($msg)){
            return redirect()->route('seller.signup')->withErrors(['errors' => $msg])->withInput($request->all());
        }


        $validIDName = \Str::slug($request->username).'.'.$request->valid_ID->extension();  
        $profilepictureName = \Str::slug($request->username).'.'.$request->profile_photo->extension(); 

        $transferid = $request->file('valid_ID')->storeAs('public/images/client/id', $validIDName);
        $transferphoto = $request->file('profile_photo')->storeAs('public/images/client/photo', $profilepictureName);

        $saveseller = Seller::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'birthdate' => $request->birth_date,
            'gender' => $request->gender,
            'civil_status' => $request->civil_status,
            'contact_number' => $request->contact_number,
            'education' => $request->education,
            'province' => 864,
            'municipality' => $Municipality,
            'barangay' => $Barangay,
            'street' => $request->street,
            'zipcode' => $request->ZipCode,
            'idnumber' => $request->idnumber,
            'idphoto' => $validIDName,
            'profile_picture' => $profilepictureName,
            'status' => 0
        ]);

        Farm::create([
            'seller_id' => $saveseller->id,
            'farm_province' => 864,
            'farm_municipality' => $farm_municipal,
            'farm_barangay' => $farm_barangay,
            'farm_size' => $request->farm_size,
            'beneficiary' => $request->program_beneficiary,
            'aid_received' => '',
            'beneficiary_specify' => $request->beneficiary,
        ]);

            // 'farm_type' => $request->crops.' '.$request->livestocks.' '.$request->vegetables.' '.$request->products,
            // 'farm_crops' => $request->crop1.' '.$request->crop2.' '.$request->crop3.' '.$request->crop4,
            // 'farm_livestocks' => $request->livestock1.' '.$request->livestock2.' '.$request->livestock3.' '.$request->livestock4,
            // 'farm_vegetables' => $request->vegetable1.' '.$request->vegetable2.' '.$request->vegetable3.' '.$request->vegetable4,
            // 'farm_products' => $request->product1.' '.$request->product2.' '.$request->product3.' '.$request->product4,
            // 'gross_harvest' => $request->harvest_gross,
            // 'net_harvest' => $request->harvest_net,
            
            

        Auth::login($user = User::create([
            'account_id' => $saveseller->id,
            'username' => $request->username,
            'user_type' => "seller",
            'email' => null,
            'password' => Hash::make($request->password),
            'secretkey' => \Str::random(16),
        ]));

        event(new Registered($user));

        return redirect(RouteServiceProvider::HOME);

    }


}
