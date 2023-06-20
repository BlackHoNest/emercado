<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalList;
use App\Models\User;
use App\Models\Admin;
use App\Models\Refbrgy;
use App\Models\Seller;
use App\Models\Buyer;
use App\Models\FarmType;
use App\Models\FarmTypeSub;
use App\Models\Farmer_farmtype;
use App\Models\Aid;
use App\Models\Farm;
use App\Models\FarmCoordinates;
use App\Models\Aid_received;

class HomeController extends Controller
{
    /*
     * Dashboard Pages Routs
     */
    public function __construct(){
        $this->globalList = new GlobalList();
        $this->aes = new AESCipher();
    }

    public function index()
    {
        $assets = ['chart', 'animation'];
        $uType = Auth::user()->user_type;

        if (strtolower($uType) == "provincial"){
            
            $totalusers = User::count();
            $totaladmins = User::where('user_type', 'municipal')->count();
            $totalverified = Seller::where('status', 'Verified')->count();
            $totalpending = Seller::where('status', 'Pending')->count();
            $totalbuyer = Buyer::count();
            $sellersbymunicipal = Admin::where('account_id', '!=', 0)->orderby('municipal', 'ASC')->get();
            $totalsellerspending = Seller::where('status', '=', 'Pending')->get();
            $totalsellersverified = Seller::where('status', '=', 'Verified')->get();
            $recentlyverified = Seller::where('status', 'Verified')->limit(5)->orderby('updated_at', 'DESC')->get();

            return view('dashboards.admin', compact('assets', 'totalusers', 'totaladmins', 'totalverified', 'totalpending', 'totalbuyer', 'recentlyverified', 'sellersbymunicipal', 'totalsellerspending', 'totalsellersverified'));
        }
        elseif (strtolower($uType) == "municipal"){

            $totalusers = Buyer::count() + Seller::where('municipality', Auth::user()->account_id)->count();
            $totalverified = Seller::where('municipality', Auth::user()->account_id)->where('status', 'Verified')->count();
            $totalpending = Seller::where('municipality', Auth::user()->account_id)->where('status', 'Pending')->count();
            $totalbuyer = Buyer::count();
            $sellersbybrgy = Refbrgy::where('citymunCode', '=', Auth::user()->account_id)->orderby('brgyDesc', 'ASC')->get();
            $totalsellerspending = Seller::where('status', '=', 'Pending')->get();
            $totalsellersverified = Seller::where('status', '=', 'Verified')->get();
            $recentlyverified = Seller::where('status', 'Verified')->where('municipality', Auth::user()->account_id)->limit(5)->orderby('updated_at', 'DESC')->get();

            return view('dashboards.admin', compact('assets', 'totalusers', 'totalverified', 'totalpending', 'totalbuyer', 'sellersbybrgy', 'totalsellerspending', 'totalsellersverified', 'recentlyverified'));
        }
        elseif (strtolower($uType) == "seller")
        {
            return view('dashboards.seller', compact('assets'));
        }
        elseif (strtolower($uType) == "buyer")
        {

        }
        else
        {

        }
    }
    public function municipaladmins() {

        $Municipalties = $this->globalList->Municipalities(864);

        if(strtolower(Auth::user()->user_type) == 'provincial') {

            $municipaladmins = User::join('admin', 'users.account_id', '=', 'admin.account_id')
                    
            ->where('admin.account_id', '!=', 0 )->orderby('municipal', 'ASC')->get();

            return view('dashboards.admin-pages.municipaladmins', compact('Municipalties', 'municipaladmins'));

        }
        else {

            return view('errors.error404');

        }
    }

    public function verifiedsellers() {

        if(strtolower(Auth::user()->user_type) == 'provincial') {

            $verifiedsellers = Seller::
                
            join('refcitymun', 'sellers.municipality', '=', 'refcitymun.citymunCode')

            ->join('refbrgy', 'sellers.barangay', '=', 'refbrgy.brgyCode')
                
            ->where(['status' => 'Verified'])->orderby('last_name', 'ASC')->get();

            return view('dashboards.admin-pages.verifiedsellers', compact('verifiedsellers'));

        }

        if(strtolower(Auth::user()->user_type) == 'municipal') {

            $verifiedsellers = Seller::
                
            join('refcitymun', 'sellers.municipality', '=', 'refcitymun.citymunCode')

            ->join('refbrgy', 'sellers.barangay', '=', 'refbrgy.brgyCode')
                
            ->where('status', 'Verified')->where('municipality', Auth::user()->account_id)->orderby('last_name', 'ASC')->get();

            return view('dashboards.admin-pages.verifiedsellers', compact('verifiedsellers'));

        }
        else {

            return view('errors.error404');

        }

    }

    public function pendingsellers() {

        if(strtolower(Auth::user()->user_type) == 'provincial') {

            $pendingsellers = Seller::
            
            join('refcitymun', 'sellers.municipality', '=', 'refcitymun.citymunCode')

            ->join('refbrgy', 'sellers.barangay', '=', 'refbrgy.brgyCode')
            
            ->where(['status' => 'Pending'])->orderby('last_name', 'ASC')->get();

            return view('dashboards.admin-pages.pendingsellers', compact('pendingsellers'));

        }

        if(strtolower(Auth::user()->user_type) == 'municipal') {

            $pendingsellers = Seller::
            
            join('refcitymun', 'sellers.municipality', '=', 'refcitymun.citymunCode')

            ->join('refbrgy', 'sellers.barangay', '=', 'refbrgy.brgyCode')

            ->where('municipality', Auth::user()->account_id)
            
            ->where(['status' => 'Pending'])

            ->orderby('last_name', 'ASC')->get();

            return view('dashboards.admin-pages.pendingsellers', compact('pendingsellers'));

        }
        else {

            return view('errors.error404');

        }

    }

    public function buyers() {

        if(strtolower(Auth::user()->user_type) == 'provincial' || strtolower(Auth::user()->user_type) == 'municipal') {

            $buyers = Buyer::
            
            join('refprovince', 'buyer.province', '=', 'refprovince.provCode')

            ->join('refcitymun', 'buyer.municipality', '=', 'refcitymun.citymunCode')

            ->join('refbrgy', 'buyer.barangay', '=', 'refbrgy.brgyCode')

            ->orderby('last_name', 'ASC')->get();

            return view('dashboards.admin-pages.buyers', compact('buyers'));

        }
        else {

            return view('errors.error404');

        }

    }

    public function farmtype() {

        if(strtolower(Auth::user()->user_type) == 'municipal' || strtolower(Auth::user()->user_type) == 'provincial') {

            $farmtypes = FarmType::orderby('description', 'ASC')->get();

            return view('dashboards.admin-pages.farmtype', compact('farmtypes'));

        }
        else {

            return view('errors.error404');

        }

    }

    public function farmproduct() {

        if(strtolower(Auth::user()->user_type) == 'municipal' || strtolower(Auth::user()->user_type) == 'provincial') {

            $farmproduct = FarmType::join('farmtype_sub', 'farmtype.id', '=', 'farmtype_sub.farmtypeid')->orderby('description', 'ASC')->get();

            $farmtypes = FarmType::orderby('description', 'ASC')->get();

            return view('dashboards.admin-pages.farmproduct', compact('farmproduct', 'farmtypes'));

        }
        else {

            return view('errors.error404');

        }

    }

    public function farmaid() {

        if(strtolower(Auth::user()->user_type) == 'municipal' || strtolower(Auth::user()->user_type) == 'provincial') {

            $farmaid = Aid::orderby('AidName', 'ASC')->get();

            return view('dashboards.admin-pages.farmaid', compact('farmaid'));

        }
        else {

            return view('errors.error404');

        }
    }

    public function pendingseller(Request $request) {

        $seller_id = (isset($request->seller_id)?$this->aes->decrypt($request->seller_id):"");

        $sellerinfo = Seller::join('refprovince', 'sellers.province', '=', 'refprovince.provCode')

            ->join('refcitymun', 'sellers.municipality', '=', 'refcitymun.citymunCode')

            ->join('refbrgy', 'sellers.barangay', '=', 'refbrgy.brgyCode')
            
            ->where(['seller_id' => $seller_id])->get();


        $sellerfarm = Farm::join('refprovince', 'farms.farm_province', '=', 'refprovince.provCode')

        ->join('refcitymun', 'farms.farm_municipality', '=', 'refcitymun.citymunCode')

        ->join('refbrgy', 'farms.farm_barangay', '=', 'refbrgy.brgyCode')

        ->where(['seller_id' => $seller_id])->get();
       

        $sellerfarmtype = Farmer_farmtype::join('farmtype', 'farmer_farmtype.farmid', '=', 'farmtype.id')

        ->join('farmtype_sub', 'farmer_farmtype.farmsubid', '=', 'farmtype_sub.id')

        ->where(['seller_id' => $seller_id])->get();


        $selleraid = Aid_received::join('aid', 'aid_received.aid_id', '=', 'aid.id')

        ->where(['seller_id' => $seller_id])->get();

        return view('dashboards.admin-pages.pending-seller', compact('sellerinfo', 'sellerfarm', 'sellerfarmtype', 'selleraid'));

    }

    public function viewseller(Request $request) {

        $seller_id = (isset($request->seller_id)?$this->aes->decrypt($request->seller_id):"");

        $sellerinfo = Seller::join('refprovince', 'sellers.province', '=', 'refprovince.provCode')

            ->join('refcitymun', 'sellers.municipality', '=', 'refcitymun.citymunCode')

            ->join('refbrgy', 'sellers.barangay', '=', 'refbrgy.brgyCode')
            
            ->where(['seller_id' => $seller_id])->get();


        $sellerfarm = Farm::join('refprovince', 'farms.farm_province', '=', 'refprovince.provCode')

        ->join('refcitymun', 'farms.farm_municipality', '=', 'refcitymun.citymunCode')

        ->join('refbrgy', 'farms.farm_barangay', '=', 'refbrgy.brgyCode')

        ->where(['seller_id' => $seller_id])->get();
       

        $sellerfarmtype = Farmer_farmtype::join('farmtype', 'farmer_farmtype.farmid', '=', 'farmtype.id')

        ->join('farmtype_sub', 'farmer_farmtype.farmsubid', '=', 'farmtype_sub.id')

        ->where(['seller_id' => $seller_id])->get();


        $selleraid = Aid_received::join('aid', 'aid_received.aid_id', '=', 'aid.id')

        ->where(['seller_id' => $seller_id])->get();

        $sellercoordinates = FarmCoordinates::where(['seller_id' => $seller_id])->get();

        return view('dashboards.admin-pages.view-seller', compact('sellerinfo', 'sellerfarm', 'sellerfarmtype', 'selleraid', 'sellercoordinates'));

    }

    public function viewbuyer(Request $request) {

        $buyer_id = (isset($request->buyer_id)?$this->aes->decrypt($request->buyer_id):"");        

        $buyerinfo = Buyer::join('refprovince', 'buyer.province', '=', 'refprovince.provCode')

        ->join('refcitymun', 'buyer.municipality', '=', 'refcitymun.citymunCode')

        ->join('refbrgy', 'buyer.barangay', '=', 'refbrgy.brgyCode')
        
        ->where(['buyer_id' => $buyer_id])->get();

        return view('dashboards.admin-pages.view-buyer', compact('buyerinfo'));

    }


     /*
     * uisheet Page Routs
     */
    public function guest(Request $request)
    {
        return view('guest');
    }



    /*
     * Auth Routs
     */
    public function signin(Request $request)
    {
        return view('auth.login');
    }

    public function provincial(Request $request)
    {
        return view('auth.superadmin');
    }


}
