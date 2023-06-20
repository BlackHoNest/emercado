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
use App\Models\Admin;
use App\Models\Farmer_farmtype;
use App\Models\Aid_received;

class AdminController extends Controller
{

    protected $globalList;
    protected $aes;

    public function __construct(){
        $this->globalList = new GlobalList();
        $this->aes = new AESCipher();
    }
    
    //CREATE - INSERT

    public function createadmin(Request $request) {

        $Municipality = (isset($request->municipal)?$this->aes->decrypt($request->municipal):"");
        $MunCode = (isset($request->muncode)?$this->aes->decrypt($request->muncode):"");

        if($Municipality == '' || $request->username == '' || $request->password == '' || $request->password != $request->password_confirmation) 
        { 
            return 0; 
        
        }
        else {

            foreach(Admin::where(['account_id' => $MunCode])->get() as $verify) { return 1; }

            foreach(User::where(['username' => $request->username])->get() as $verify) { return 1; }

            $saveadmin = Admin::create([
                'account_id' => $MunCode,
                'municipal' => strtolower($Municipality),
                'province' => $request->province,
            ]);

            User::create([
                'account_id' => $saveadmin->account_id,
                'username' => $request->username,
                'user_type' => "municipal",
                'email' => null,
                'password' => Hash::make($request->password),
                'secretkey' => \Str::random(16),
            ]);

            return 2;

        }

    }

    public function createfarm(Request $request) {

        if($request->farmdescription == '') 
        { 
            return 0; 
        }

        else {

            foreach(FarmType::where(['description' => $request->farmdescription])->get() as $verify) { return 1; }

            FarmType::create(['description' => $request->farmdescription]);

            return 2;

        }

    }

    public function createfarmproduct(Request $request) {

        $farmtype = (isset($request->farmtype)?$this->aes->decrypt($request->farmtype):"");

        if($request->productdescription == '') 
        { 
            return 0; 
        }

        else {

            foreach(FarmTypeSub::where(['product_description' => $request->productdescription])->get() as $verify) { return 1; }
        
            FarmTypeSub::create([
                'farmtypeid' => $farmtype,
                'product_description' => $request->productdescription
            ]);

            return 2;

        }

    }

    public function createaid(Request $request) {

        if($request->aiddescription == '') 
        { 
            return 0; 
        }
        else {

            foreach(Aid::where(['AidName' => $request->aiddescription])->get() as $verify) { return 1; }

            Aid::create(['AidName' => $request->aiddescription]);

            return 2;

        }

    }



//UPDATE - MODIFY

    public function updateadmin(Request $request) {
        
        $Municipality = (isset($request->municipal)?$this->aes->decrypt($request->municipal):"");
        $MunCode = (isset($request->muncode)?$this->aes->decrypt($request->muncode):"");
        $updateid = (isset($request->updateid)?$this->aes->decrypt($request->updateid):"");

        if($Municipality == '' || $request->username == '' || $request->password == '' || $request->password == '' || $request->password_confirmation == '') 
        { 
            return 0; 
        
        }
        else {

            foreach(User::where(['account_id' => $updateid])->get() as $verify) {
                if(Hash::check($request->password, $verify->password)) {
                    break;
                }
                else {
                    return 1;
                }
            }
         
            User::where(['account_id' => $updateid])
            ->update([
                'account_id' => $MunCode,
                'username' => $request->username,
                'user_type' => "municipal",
                'email' => null,
                'password' => Hash::make($request->password_confirmation),
                'secretkey' => \Str::random(16),
            ]);

            Admin::where(['account_id' => $updateid])
            ->update([
                'account_id' => $MunCode,
                'municipal' => strtolower($Municipality),
                'province' => $request->province
            ]);

            return 2;

        }

    }

    public function updatefarm(Request $request) {

        if($request->farmdescription == '') 
        { 
            return 0; 
        }

        else {

            FarmType::where(['id' => $request->updateid])->update(['description' => $request->farmdescription]);

            return 2;

        }

    }

    public function updatefarmproduct(Request $request) {

        $farmtype = (isset($request->farmtype)?$this->aes->decrypt($request->farmtype):"");

        if($request->productdescription == '') 
        { 
            return 0; 
        }

        else {

            FarmTypeSub::where(['id' => $request->updateid])
            ->update([
                'farmtypeid' => $farmtype,
                'product_description' => $request->productdescription
            ]);
                
            return 2;

        }

    }

    public function updateaid(Request $request) {

        if($request->aiddescription == '') 
        { 
            return 0; 
        }
        else {

            Aid::where(['id' => $request->updateid])->update(['AidName' => $request->aiddescription]);

            return 2;

        }

    }
}
