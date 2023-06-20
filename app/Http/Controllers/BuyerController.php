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

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AESCipher;
use Illuminate\Support\Carbon;

use App\Models\FarmType;
use App\Models\FarmTypeSub;
use App\Models\UOM;
use App\Models\PostProduct;
use App\Models\SellerImage;
use App\Models\Cart;

class BuyerController extends Controller
{

    protected $globalList;
    protected $aes;
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

    public function index(){

    }

    // wala pa ni diri na edit
    public function create(Request $request)
    {
     
        $pageTitle = "Post New Product";
        $headerAction = '<a href="'.route('post.index').'" class="btn btn-sm btn-primary" role="button">Back</a>';
        $buttonCaption = "Post New Product";
        $farmtypes = FarmType::orderBy('description','ASC')->get();
        $uoms = UOM::orderby("UnitName", "ASC")->get();

        $farmsub = [];
        if (!empty(old('ProductType'))){
            $farmsub = FarmTypeSub::where('farmtypeid', $this->aes->decrypt(old('ProductType')))->get();
        }

        return view('post.form', compact('farmtypes','uoms','farmsub'), [
            'pageTitle' => $pageTitle,
            'headerAction' => $headerAction,
            'buttonCaption' => $buttonCaption,
            'form' => 'post'
        ]);

    }

    public function store(Request $request){

        $request->validate([
            'Title' => 'required|string|max:255',
            'ProductType' => 'required|string|max:255',
            'ProductTypeSub' => 'required|string',
            'Stocks' => 'required|numeric',
            'Amount' => 'required|numeric',
            'UOM' => 'required|string',
            'Description' => 'required|string'
        ]);

        $checkAvailable = (isset($request->switchstock)?$request->switchstock:0);
        $dateAvailable = $request->HarvestDate;
        if (empty($checkAvailable )){
            if (empty($dateAvailable)){
                return redirect()->route('post.create')->withErrors(['errors' => "Please set date of target harvest"])->withInput($request->all());
            }
        }

        $producttype = (isset($request->ProductType)?$this->aes->decrypt($request->ProductType):"");
        $productkind = (isset($request->ProductTypeSub)?$this->aes->decrypt($request->ProductTypeSub):"");
        $uom = (isset($request->UOM)?$this->aes->decrypt($request->UOM):"");

        $posts = PostProduct::insert([
            'SellerID' => Auth::user()->account_id,
            'Title' => $request->Title,
            'ProductType' => $producttype,
            'ProductKind' => $productkind,
            'Stocks' => $request->Stocks,
            'UOM' => $uom,
            'StockAvailable' => $checkAvailable,
            'AvailableDate' => ($checkAvailable == 1? null : $dateAvailable."-01"),
            'Description' => $request->Description,
            'Remarks' => $request->remarks,
            'draft' => 0,
            'Amount' => $request->Amount,
            'created_at' => Carbon::now(),
            'slug_name' => \Str::slug($request->Title)
        ]);

        if ($posts){
            return redirect()->route('post.drafts')->withSuccess("Product successfully saved in draft.");
        }

        return redirect()->route('post.create')->withErrors(['errors' => "Unable to save new product to post"])->withInput($request->all());
    }

    public function drafts(){
        
        $drafts = PostProduct::where("draft", 0)
            ->where('SellerID', Auth::user()->account_id)
            ->orderBy('created_at', 'DESC')->get();
        
        $pageTitle = "TO BE POSTED";
        $headerAction = '<a href="'.route('post.create').'" class="btn btn-sm btn-primary" role="button">Post New Product</a>';


        return view('post.drafts', compact('drafts'), [
            'pageTitle' => $pageTitle,
            'headerAction' => $headerAction,
        ]);


    }
}
