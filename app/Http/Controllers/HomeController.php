<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LogController;

use App\Models\FarmType;
use App\Models\PostProduct;
use App\Models\Order;

class HomeController extends Controller
{
    /*
     * Dashboard Pages Routs
     */
    public function index(Request $request)
    {
        $assets = ['chart', 'animation'];
        $uType = Auth::user()->user_type;

        $cart = new CartController();
        $ctr = $cart->badge();

        $log = new LogController();

        if (strtolower($uType) == "seller"){

            $posts = PostProduct::select('draft', DB::raw('count(draft) as countPost'))
                ->where("SellerID", Auth::user()->account_id)
                ->groupBy("draft")
                ->get();

            $orders = Order::select('orderstatus', DB::raw('count(orderstatus) as countOrder'))
                ->where("SellerID", Auth::user()->account_id)
                ->groupBy("orderstatus")
                ->get();
                
            $top5 = Order::select('BuyerID', DB::raw('count(BuyerID) as countBuyer'))
                ->where("SellerID", Auth::user()->account_id)
                ->groupBy("BuyerID")
                ->orderBy('countBuyer', 'DESC')
                ->get();

            
            return view('dashboards.seller', compact('assets','posts','orders','top5'),[
                'logs' => $log->show(5)
            ]);

        }elseif (strtolower($uType) == "buyer"){

            $posteds = PostProduct::where("draft", 1)->orderBy('created_at', 'DESC')->paginate(10);

            return view('dashboards.buyer', compact('assets','posteds'),[
                'Badge' => $ctr,
                'logs' => $log->show(5)
            ]);

        }else{

        }
       
    }


    /*
     * uisheet Page Routs
     */
    public function guest(Request $request)
    {
        $cart = new CartController();
        $ctr = $cart->badge();
        $posteds = PostProduct::where("draft", 1)->orderBy('created_at', 'DESC')->paginate(15);
        $categories = FarmType::orderBy("description", 'ASC')->get();
        return view('guest', compact('categories','posteds'),[
            'Badge' => $ctr
        ]);
    }

 
    public function signin(Request $request)
    {
        return view('auth.login');
    }

    public function error404(Request $request)
    {
        return view('errors.error404');
    }

}
