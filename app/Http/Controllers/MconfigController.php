<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MconfigController extends Controller
{
    //
    public function index() {
        $lists = DB::table('app_setting')->first();
        $list = [] ;
        $list = (array) $lists ;
        return view('admin/config',['data' => $list, 'success' => '2','psuccess' => '2']) ;
    }

    public function changepayment(Request $request) {
        $data = $request->all() ;
        $affected = DB::update('update app_setting set currency_symbol = "'.$data['currency_symbol'].'", stripe_key = "'.$data['stripe_key'].'"');
        $lists = DB::table('app_setting')->first();
        $list = [] ;
        $list = (array) $lists ;
        if($affected) {
            return view('admin/config',['data' => $list,'success' => '1', 'psuccess' => '2']) ;
        }
        return view('admin/config',['data' => $list,'success' => '0', 'psuccess' => '2']) ;
    }

    public function changegoogle(Request $request) {
        $data = $request->all() ;
        $affected = DB::update('update app_setting set map_key = "'.$data['map_key'].'", android_key = "'.$data['android_key'].'", 
            ios_key = "'.$data['ios_key'].'"');
        $lists = DB::table('app_setting')->first();
        $list = [] ;
        $list = (array) $lists ;
        if($affected) {
            return view('admin/config',['data' => $list,'success' => '2', 'psuccess' => '1']) ;
        }
        return view('admin/config',['data' => $list,'success' => '2', 'psuccess' => '0']) ;
    }
}
