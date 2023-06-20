<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AESCipher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Models\PostProduct;

class ShareController extends Controller
{

    protected $aes;

    public function __construct(){

        $this->aes = new AESCipher();
    }

    public function product($id){


        $data = PostProduct::where('slug_name', $id)->first();
    
        return view('share.product', compact('data'));
    }
}
